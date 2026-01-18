<?php

class Employee extends Model
{
    protected $table = 'employees';

    public function findByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$userId]);
    }

    public function findWithUser($id)
    {
        $sql = "SELECT e.*, u.email, u.role, u.is_active 
                FROM {$this->table} e
                INNER JOIN users u ON e.user_id = u.id
                WHERE e.id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function getWithUser($id)
    {
        // Alias for findWithUser for API consistency
        return $this->findWithUser($id);
    }

    public function getAllWithUsers($filters = [])
    {
        $sql = "SELECT e.*, u.email, u.role, u.is_active,
                       m.full_name as manager_name
                FROM {$this->table} e
                INNER JOIN users u ON e.user_id = u.id
                LEFT JOIN employees m ON e.manager_id = m.id";
        
        $where = [];
        $params = [];

        if (!empty($filters['department'])) {
            $where[] = "e.department = ?";
            $params[] = $filters['department'];
        }

        if (!empty($filters['status'])) {
            $where[] = "e.employment_status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $where[] = "(e.full_name LIKE ? OR e.employee_id LIKE ? OR u.email LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY e.full_name ASC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getManager($employeeId)
    {
        $sql = "SELECT m.* FROM {$this->table} e
                INNER JOIN employees m ON e.manager_id = m.id
                WHERE e.id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$employeeId]);
    }

    public function getDirectReports($managerId)
    {
        return $this->findAll(['manager_id' => $managerId], 'full_name ASC');
    }

    public function getDepartments()
    {
        $sql = "SELECT DISTINCT department FROM {$this->table} ORDER BY department";
        return array_column($this->db->fetchAll($sql), 'department');
    }
}
