<?php

class LeaveService
{
    private $workflowService;
    private $db;
    private $auditLog;

    public function __construct()
    {
        $this->workflowService = new WorkflowService();
        $this->db = Database::getInstance();
        $this->auditLog = new AuditLog();
    }

    public function getLeaveTypes()
    {
        $sql = "SELECT * FROM leave_types ORDER BY name";
        return $this->db->fetchAll($sql);
    }

    public function getLeaveBalance($employeeId, $year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $sql = "SELECT lb.*, lt.name as leave_type_name, lt.code
                FROM leave_balances lb
                INNER JOIN leave_types lt ON lb.leave_type_id = lt.id
                WHERE lb.employee_id = ? AND lb.year = ?";
        return $this->db->fetchAll($sql, [$employeeId, $year]);
    }

    public function initializeLeaveBalance($employeeId, $designation, $year = null)
    {
        if (!$year) {
            $year = date('Y');
        }

        $sql = "SELECT lq.leave_type_id, lq.annual_quota
                FROM leave_quotas lq
                WHERE lq.designation = ?";
        $quotas = $this->db->fetchAll($sql, [$designation]);

        foreach ($quotas as $quota) {
            // Check if balance already exists
            $existing = $this->db->fetchOne(
                "SELECT id FROM leave_balances WHERE employee_id = ? AND leave_type_id = ? AND year = ?",
                [$employeeId, $quota['leave_type_id'], $year]
            );

            if (!$existing) {
                $this->db->execute(
                    "INSERT INTO leave_balances (employee_id, leave_type_id, year, quota, used, remaining)
                     VALUES (?, ?, ?, ?, 0, ?)",
                    [$employeeId, $quota['leave_type_id'], $year, $quota['annual_quota'], $quota['annual_quota']]
                );
            }
        }
    }

    public function submitLeaveRequest($employeeId, $leaveTypeId, $startDate, $endDate, $reason)
    {
        // Calculate days
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $days = $end->diff($start)->days + 1;

        // Check balance
        $year = date('Y', strtotime($startDate));
        $balance = $this->db->fetchOne(
            "SELECT * FROM leave_balances WHERE employee_id = ? AND leave_type_id = ? AND year = ?",
            [$employeeId, $leaveTypeId, $year]
        );

        if (!$balance || $balance['remaining'] < $days) {
            throw new Exception("Insufficient leave balance");
        }

        // Get leave type info
        $leaveType = $this->db->fetchOne("SELECT * FROM leave_types WHERE id = ?", [$leaveTypeId]);

        // Create work item
        $metadata = [
            'leave_type_id' => $leaveTypeId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days' => $days,
            'reason' => $reason
        ];

        $title = "{$leaveType['name']} - $startDate to $endDate ($days days)";
        
        return $this->workflowService->createWorkItem(
            'leave_request',
            $title,
            $reason,
            $employeeId,
            null,
            'medium',
            null,
            null,
            $metadata
        );
    }

    public function approveLeaveRequest($workItemId, $employeeId)
    {
        $workItem = $this->db->fetchOne("SELECT * FROM work_items WHERE id = ?", [$workItemId]);
        if (!$workItem || $workItem['type'] !== 'leave_request') {
            throw new Exception("Invalid leave request");
        }

        $metadata = json_decode($workItem['metadata'], true);
        
        // Deduct from balance
        $year = date('Y', strtotime($metadata['start_date']));
        $this->db->execute(
            "UPDATE leave_balances 
             SET used = used + ?, remaining = remaining - ?
             WHERE employee_id = ? AND leave_type_id = ? AND year = ?",
            [$metadata['days'], $metadata['days'], $workItem['created_by'], $metadata['leave_type_id'], $year]
        );

        // Audit log
        $this->auditLog->log(
            Session::get('user')['id'],
            'approve_leave',
            'work_item',
            $workItemId,
            $metadata
        );

        return true;
    }
}
