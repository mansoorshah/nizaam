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
}
