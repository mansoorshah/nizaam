<?php

class AuditLog extends Model
{
    protected $table = 'audit_log';

    public function log($actorId, $action, $entityType, $entityId, $metadata = null, $ipAddress = null, $userAgent = null)
    {
        return $this->create([
            'actor_id' => $actorId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'metadata' => $metadata ? json_encode($metadata) : null,
            'ip_address' => $ipAddress ?? Request::ip(),
            'user_agent' => $userAgent ?? Request::userAgent()
        ]);
    }

    public function getForEntity($entityType, $entityId)
    {
        $sql = "SELECT al.*, u.email as actor_email
                FROM {$this->table} al
                LEFT JOIN users u ON al.actor_id = u.id
                WHERE al.entity_type = ? AND al.entity_id = ?
                ORDER BY al.created_at DESC";
        return $this->db->fetchAll($sql, [$entityType, $entityId]);
    }

    public function getRecent($limit = 100)
    {
        $sql = "SELECT al.*, u.email as actor_email
                FROM {$this->table} al
                LEFT JOIN users u ON al.actor_id = u.id
                ORDER BY al.created_at DESC
                LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }

    public function search($filters = [])
    {
        $sql = "SELECT al.*, u.email as actor_email
                FROM {$this->table} al
                LEFT JOIN users u ON al.actor_id = u.id";
        
        $where = [];
        $params = [];

        if (!empty($filters['actor_id'])) {
            $where[] = "al.actor_id = ?";
            $params[] = $filters['actor_id'];
        }

        if (!empty($filters['entity_type'])) {
            $where[] = "al.entity_type = ?";
            $params[] = $filters['entity_type'];
        }

        if (!empty($filters['action'])) {
            $where[] = "al.action = ?";
            $params[] = $filters['action'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = "DATE(al.created_at) >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = "DATE(al.created_at) <= ?";
            $params[] = $filters['date_to'];
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY al.created_at DESC LIMIT 500";

        return $this->db->fetchAll($sql, $params);
    }
}
