<?php

class Workflow extends Model
{
    protected $table = 'workflows';

    public function findByType($workType)
    {
        $sql = "SELECT * FROM {$this->table} WHERE work_type = ? AND is_active = 1 LIMIT 1";
        return $this->db->fetchOne($sql, [$workType]);
    }

    public function getStatuses($workflowId)
    {
        $sql = "SELECT * FROM workflow_statuses 
                WHERE workflow_id = ? 
                ORDER BY status_order ASC";
        return $this->db->fetchAll($sql, [$workflowId]);
    }

    public function getInitialStatus($workflowId)
    {
        $sql = "SELECT * FROM workflow_statuses 
                WHERE workflow_id = ? AND is_initial = 1 
                LIMIT 1";
        return $this->db->fetchOne($sql, [$workflowId]);
    }

    public function getStatusById($statusId)
    {
        $sql = "SELECT * FROM workflow_statuses WHERE id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$statusId]);
    }

    public function getTransitions($workflowId, $fromStatusId = null)
    {
        $sql = "SELECT wt.*, 
                       fs.status_name as from_status_name,
                       ts.status_name as to_status_name
                FROM workflow_transitions wt
                INNER JOIN workflow_statuses fs ON wt.from_status_id = fs.id
                INNER JOIN workflow_statuses ts ON wt.to_status_id = ts.id
                WHERE wt.workflow_id = ?";
        
        $params = [$workflowId];
        
        if ($fromStatusId !== null) {
            $sql .= " AND wt.from_status_id = ?";
            $params[] = $fromStatusId;
        }

        return $this->db->fetchAll($sql, $params);
    }

    public function canTransition($workflowId, $fromStatusId, $toStatusId)
    {
        $sql = "SELECT COUNT(*) as count FROM workflow_transitions 
                WHERE workflow_id = ? AND from_status_id = ? AND to_status_id = ?";
        $result = $this->db->fetchOne($sql, [$workflowId, $fromStatusId, $toStatusId]);
        return $result['count'] > 0;
    }

    public function requiresApproval($workflowId, $fromStatusId, $toStatusId)
    {
        $sql = "SELECT requires_approval, approver_role FROM workflow_transitions 
                WHERE workflow_id = ? AND from_status_id = ? AND to_status_id = ?";
        return $this->db->fetchOne($sql, [$workflowId, $fromStatusId, $toStatusId]);
    }
}
