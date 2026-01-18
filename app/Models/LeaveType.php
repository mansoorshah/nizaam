<?php

class LeaveType extends Model
{
    protected $table = 'leave_types';

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY name";
        return $this->db->query($sql);
    }
}
