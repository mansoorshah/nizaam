<?php

class WorkItem extends Model
{
    protected $table = 'work_items';

    public function getWithDetails($id)
    {
        $sql = "SELECT wi.*, 
                       c.full_name as creator_name, c.employee_id as creator_emp_id,
                       a.full_name as assignee_name, a.employee_id as assignee_emp_id,
                       ws.status_name, ws.color as status_color,
                       w.name as workflow_name,
                       p.name as project_name
                FROM {$this->table} wi
                INNER JOIN employees c ON wi.created_by = c.id
                LEFT JOIN employees a ON wi.assigned_to = a.id
                INNER JOIN workflow_statuses ws ON wi.current_status_id = ws.id
                INNER JOIN workflows w ON wi.workflow_id = w.id
                LEFT JOIN projects p ON wi.project_id = p.id
                WHERE wi.id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$id]);
    }

    public function getAll($filters = [])
    {
        $sql = "SELECT wi.*, 
                       c.full_name as creator_name,
                       a.full_name as assignee_name,
                       ws.status_name, ws.color as status_color,
                       p.name as project_name
                FROM {$this->table} wi
                INNER JOIN employees c ON wi.created_by = c.id
                LEFT JOIN employees a ON wi.assigned_to = a.id
                INNER JOIN workflow_statuses ws ON wi.current_status_id = ws.id
                LEFT JOIN projects p ON wi.project_id = p.id";
        
        $where = [];
        $params = [];

        if (!empty($filters['type'])) {
            $where[] = "wi.type = ?";
            $params[] = $filters['type'];
        }

        if (!empty($filters['created_by'])) {
            $where[] = "wi.created_by = ?";
            $params[] = $filters['created_by'];
        }

        if (!empty($filters['assigned_to'])) {
            $where[] = "wi.assigned_to = ?";
            $params[] = $filters['assigned_to'];
        }

        if (!empty($filters['project_id'])) {
            $where[] = "wi.project_id = ?";
            $params[] = $filters['project_id'];
        }

        if (!empty($filters['priority'])) {
            $where[] = "wi.priority = ?";
            $params[] = $filters['priority'];
        }

        if (!empty($filters['status_id'])) {
            $where[] = "wi.current_status_id = ?";
            $params[] = $filters['status_id'];
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY wi.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getHistory($workItemId)
    {
        $sql = "SELECT wih.*, 
                       fs.status_name as from_status_name, fs.color as from_status_color,
                       ts.status_name as to_status_name, ts.color as to_status_color,
                       e.full_name as changed_by_name
                FROM work_item_history wih
                LEFT JOIN workflow_statuses fs ON wih.from_status_id = fs.id
                INNER JOIN workflow_statuses ts ON wih.to_status_id = ts.id
                INNER JOIN employees e ON wih.changed_by = e.id
                WHERE wih.work_item_id = ?
                ORDER BY wih.created_at DESC";
        return $this->db->fetchAll($sql, [$workItemId]);
    }

    public function addHistory($workItemId, $fromStatusId, $toStatusId, $changedBy, $comment = null)
    {
        $sql = "INSERT INTO work_item_history (work_item_id, from_status_id, to_status_id, changed_by, comment)
                VALUES (?, ?, ?, ?, ?)";
        return $this->db->execute($sql, [$workItemId, $fromStatusId, $toStatusId, $changedBy, $comment]);
    }

    public function updateStatus($workItemId, $statusId, $changedBy, $comment = null)
    {
        $workItem = $this->find($workItemId);
        if (!$workItem) {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $this->update($workItemId, ['current_status_id' => $statusId]);
            $this->addHistory($workItemId, $workItem['current_status_id'], $statusId, $changedBy, $comment);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}
