<?php

class Notification extends Model
{
    protected $table = 'notifications';

    public function getForEmployee($employeeId, $limit = 50)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE employee_id = ? 
                ORDER BY created_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$employeeId, $limit]);
    }

    public function getUnreadCount($employeeId)
    {
        return $this->count(['employee_id' => $employeeId, 'is_read' => 0]);
    }

    public function markAsRead($notificationId)
    {
        return $this->update($notificationId, ['is_read' => 1]);
    }

    public function markAllAsRead($employeeId)
    {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE employee_id = ? AND is_read = 0";
        return $this->db->execute($sql, [$employeeId]);
    }

    public function createNotification($employeeId, $title, $message, $type = 'info', $relatedType = null, $relatedId = null)
    {
        return $this->create([
            'employee_id' => $employeeId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'is_read' => 0
        ]);
    }
}
