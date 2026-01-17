<?php

class AuditController extends Controller
{
    private $auditLog;

    public function __construct()
    {
        parent::__construct();
        $this->auditLog = new AuditLog();
    }

    public function index()
    {
        $filters = [
            'entity_type' => Request::input('entity_type'),
            'action' => Request::input('action'),
            'date_from' => Request::input('date_from'),
            'date_to' => Request::input('date_to')
        ];

        $logs = $this->auditLog->search($filters);

        $this->view('audit.index', [
            'logs' => $logs,
            'filters' => $filters
        ]);
    }
}
