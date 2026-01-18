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
        $notifications = $this->db->fetchAll($sql, [$employeeId, $limit]);
        
        // Generate links for notifications based on type and related_id
        foreach ($notifications as &$notification) {
            if (!isset($notification['link']) || empty($notification['link'])) {
                $notification['link'] = $this->generateLink($notification);
            }
        }
        
        return $notifications;
    }
    
    private function generateLink($notification)
    {
        if (empty($notification['related_type']) || empty($notification['related_id'])) {
            return null;
        }
        
        switch ($notification['related_type']) {
            case 'work_item':
                return '/nizaam/public/work-items/' . $notification['related_id'];
            case 'project':
                return '/nizaam/public/projects/' . $notification['related_id'];
            case 'employee':
                return '/nizaam/public/employees/' . $notification['related_id'];
            default:
                return null;
        }
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
