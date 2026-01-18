<?php

class Project extends Model
{
    protected $table = 'projects';

    public function getWithOwner($id)
    {
        $sql = "SELECT p.*, e.full_name as owner_name
                FROM {$this->table} p
                INNER JOIN employees e ON p.owner_id = e.id
                WHERE p.id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function getWithDetails($id)
    {
        // Alias for getWithOwner for API consistency
        return $this->getWithOwner($id);
    }

    public function getAllWithOwners($filters = [])
    {
        $sql = "SELECT p.*, e.full_name as owner_name,
                       (SELECT COUNT(*) FROM project_members WHERE project_id = p.id) as member_count,
                       (SELECT COUNT(*) FROM work_items WHERE project_id = p.id) as work_item_count
                FROM {$this->table} p
                INNER JOIN employees e ON p.owner_id = e.id";
        
        $where = [];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = "p.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY p.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getMembers($projectId)
    {
        $sql = "SELECT e.*, pm.created_at as joined_at
                FROM project_members pm
                INNER JOIN employees e ON pm.employee_id = e.id
                WHERE pm.project_id = ?
                ORDER BY e.full_name";
        return $this->db->fetchAll($sql, [$projectId]);
    }

    public function addMember($projectId, $employeeId)
    {
        $sql = "INSERT IGNORE INTO project_members (project_id, employee_id) VALUES (?, ?)";
        return $this->db->execute($sql, [$projectId, $employeeId]);
    }

    public function removeMember($projectId, $employeeId)
    {
        $sql = "DELETE FROM project_members WHERE project_id = ? AND employee_id = ?";
        return $this->db->execute($sql, [$projectId, $employeeId]);
    }

    public function isMember($projectId, $employeeId)
    {
        $sql = "SELECT COUNT(*) as count FROM project_members WHERE project_id = ? AND employee_id = ?";
        $result = $this->db->fetchOne($sql, [$projectId, $employeeId]);
        return $result['count'] > 0;
    }
}
