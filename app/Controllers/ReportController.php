<?php

class ReportController extends Controller
{
    private $workItemModel;
    private $employeeModel;

    public function __construct()
    {
        parent::__construct();
        $this->workItemModel = new WorkItem();
        $this->employeeModel = new Employee();
    }

    public function index()
    {
        // Work items by status
        $statusReport = $this->db->fetchAll("
            SELECT ws.status_name, ws.color, COUNT(*) as count
            FROM work_items wi
            INNER JOIN workflow_statuses ws ON wi.current_status_id = ws.id
            GROUP BY ws.id, ws.status_name, ws.color
            ORDER BY count DESC
        ");

        // Work items by type
        $typeReport = $this->db->fetchAll("
            SELECT type, COUNT(*) as count
            FROM work_items
            GROUP BY type
        ");

        // Employee workload
        $workloadReport = $this->db->fetchAll("
            SELECT e.full_name, e.department, COUNT(wi.id) as work_item_count
            FROM employees e
            LEFT JOIN work_items wi ON e.id = wi.assigned_to
            WHERE e.employment_status = 'active'
            GROUP BY e.id, e.full_name, e.department
            ORDER BY work_item_count DESC
            LIMIT 20
        ");

        $this->view('reports.index', [
            'statusReport' => $statusReport,
            'typeReport' => $typeReport,
            'workloadReport' => $workloadReport
        ]);
    }

    public function exportWorkItems()
    {
        // Get all work items with details
        $workItems = $this->db->fetchAll("
            SELECT 
                wi.id,
                wi.title,
                wi.type,
                wi.priority,
                wi.due_date,
                creator.full_name as created_by_name,
                assignee.full_name as assigned_to_name,
                ws.status_name,
                p.name as project_name,
                wi.created_at,
                wi.updated_at
            FROM work_items wi
            LEFT JOIN employees creator ON wi.created_by = creator.id
            LEFT JOIN employees assignee ON wi.assigned_to = assignee.id
            INNER JOIN workflow_statuses ws ON wi.current_status_id = ws.id
            LEFT JOIN projects p ON wi.project_id = p.id
            ORDER BY wi.created_at DESC
        ");

        $this->generateCSV($workItems, 'work_items_report_' . date('Y-m-d'), [
            'ID', 'Title', 'Type', 'Priority', 'Due Date', 'Created By', 'Assigned To', 
            'Status', 'Project', 'Created At', 'Updated At'
        ]);
    }

    public function exportEmployeeWorkload()
    {
        $workload = $this->db->fetchAll("
            SELECT 
                e.employee_id,
                e.full_name,
                e.email,
                e.department,
                e.designation,
                COUNT(CASE WHEN wi.type = 'task' THEN 1 END) as tasks,
                COUNT(CASE WHEN wi.type = 'leave' THEN 1 END) as leaves,
                COUNT(CASE WHEN wi.type = 'expense' THEN 1 END) as expenses,
                COUNT(CASE WHEN wi.type = 'timesheet' THEN 1 END) as timesheets,
                COUNT(wi.id) as total_work_items
            FROM employees e
            LEFT JOIN work_items wi ON e.id = wi.assigned_to OR e.id = wi.created_by
            WHERE e.employment_status = 'active'
            GROUP BY e.id, e.employee_id, e.full_name, e.email, e.department, e.designation
            ORDER BY total_work_items DESC
        ");

        $this->generateCSV($workload, 'employee_workload_' . date('Y-m-d'), [
            'Employee ID', 'Name', 'Email', 'Department', 'Designation', 
            'Tasks', 'Leaves', 'Expenses', 'Timesheets', 'Total Items'
        ]);
    }

    public function exportLeaveUsage()
    {
        $leaveUsage = $this->db->fetchAll("
            SELECT 
                e.employee_id,
                e.full_name,
                e.department,
                lt.name as leave_type,
                lb.quota,
                lb.used,
                (lb.quota - lb.used) as remaining,
                lb.year
            FROM employees e
            INNER JOIN leave_balances lb ON e.id = lb.employee_id
            INNER JOIN leave_types lt ON lb.leave_type_id = lt.id
            WHERE e.employment_status = 'active'
            ORDER BY e.full_name, lt.name
        ");

        $this->generateCSV($leaveUsage, 'leave_usage_' . date('Y-m-d'), [
            'Employee ID', 'Name', 'Department', 'Leave Type', 
            'Quota', 'Used', 'Remaining', 'Year'
        ]);
    }

    private function generateCSV($data, $filename, $headers)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        
        // Write headers
        fputcsv($output, $headers);
        
        // Write data
        foreach ($data as $row) {
            fputcsv($output, array_values($row));
        }
        
        fclose($output);
        exit;
    }
}
