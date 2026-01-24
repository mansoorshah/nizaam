<?php

class TimesheetController extends Controller
{
    private $workflowService;
    private $workItemModel;

    public function __construct()
    {
        parent::__construct();
        $this->workflowService = new WorkflowService();
        $this->workItemModel = new WorkItem();
    }

    public function index()
    {
        $employee = $this->getCurrentEmployee();
        
        // Get all timesheets for current employee
        $sql = "SELECT wi.*, wfs.status_name, wfs.color as status_color
                FROM work_items wi
                INNER JOIN workflow_statuses wfs ON wi.current_status_id = wfs.id
                WHERE wi.created_by = ? AND wi.type = 'timesheet'
                ORDER BY wi.created_at DESC";
        
        $timesheets = $this->db->fetchAll($sql, [$employee['id']]);
        
        // Calculate statistics
        $stats = [
            'current_month_hours' => 0,
            'current_month_count' => 0,
            'pending_count' => 0,
            'avg_weekly_hours' => 0
        ];
        
        $totalHours = 0;
        $count = 0;
        
        foreach ($timesheets as $timesheet) {
            $metadata = json_decode($timesheet['metadata'], true);
            
            // Calculate hours from new structure (entries) or old structure (hours)
            $weekHours = 0;
            if (isset($metadata['total_hours'])) {
                $weekHours = $metadata['total_hours'];
            } elseif (isset($metadata['entries']) && is_array($metadata['entries'])) {
                foreach ($metadata['entries'] as $entry) {
                    if (isset($entry['hours']) && is_array($entry['hours'])) {
                        $weekHours += array_sum($entry['hours']);
                    }
                }
            } elseif (isset($metadata['hours']) && is_array($metadata['hours'])) {
                $weekHours = array_sum($metadata['hours']);
            }
            
            $totalHours += $weekHours;
            $count++;
            
            // Current month stats
            $createdMonth = date('Y-m', strtotime($timesheet['created_at']));
            $currentMonth = date('Y-m');
            if ($createdMonth === $currentMonth) {
                $stats['current_month_hours'] += $weekHours;
                $stats['current_month_count']++;
            }
            
            // Pending count
            if (in_array($timesheet['status_name'], ['Submitted', 'Open', 'Pending'])) {
                $stats['pending_count']++;
            }
        }
        
        if ($count > 0) {
            $stats['avg_weekly_hours'] = $totalHours / $count;
        }
        
        $this->view('timesheets.index', [
            'timesheets' => $timesheets,
            'stats' => $stats
        ]);
    }

    public function create()
    {
        $this->view('timesheets.create');
    }

    public function store()
    {
        if (!Request::isPost()) {
            $this->redirect('/timesheets/create');
            return;
        }

        try {
            $employee = $this->getCurrentEmployee();
            $weekPeriod = Request::input('week_period'); // Format: 2026-W03
            $entries = Request::input('entries');
            
            if (empty($entries) || !is_array($entries)) {
                throw new Exception('Please add at least one project entry');
            }
            
            // Calculate total hours across all entries
            $totalHours = 0;
            foreach ($entries as $entry) {
                if (isset($entry['hours']) && is_array($entry['hours'])) {
                    $totalHours += array_sum($entry['hours']);
                }
            }
            
            // Convert week period to readable format
            $year = (int)substr($weekPeriod, 0, 4);
            $week = (int)substr($weekPeriod, 6);
            $weekStart = new DateTime();
            $weekStart->setISODate($year, $week);
            $weekEnd = clone $weekStart;
            $weekEnd->modify('+6 days');
            
            $metadata = [
                'entries' => $entries,
                'total_hours' => $totalHours,
                'week_period' => $weekPeriod,
                'week_start' => $weekStart->format('Y-m-d'),
                'week_end' => $weekEnd->format('Y-m-d')
            ];

            $title = 'Timesheet: Week ' . $week . ', ' . $year . ' (' . number_format($totalHours, 1) . ' hours)';
            
            $workItemId = $this->workflowService->createWorkItem(
                'timesheet',
                $title,
                '',
                $employee['id'],
                null,
                'medium',
                null,
                null,
                $metadata
            );

            Session::flash('success', 'Timesheet submitted successfully');
            $this->redirect('/work-items/' . $workItemId);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/timesheets/create');
        }
    }

    public function show($id)
    {
        $employee = $this->getCurrentEmployee();
        
        // Get timesheet details
        $sql = "SELECT wi.*, wfs.status_name, wfs.color as status_color
                FROM work_items wi
                INNER JOIN workflow_statuses wfs ON wi.current_status_id = wfs.id
                WHERE wi.id = ? AND wi.type = 'timesheet'";
        
        $timesheet = $this->db->fetchOne($sql, [$id]);
        
        if (!$timesheet) {
            Session::flash('error', 'Timesheet not found');
            $this->redirect('/timesheets');
            return;
        }
        
        // Check access - only creator or admin can view
        if ($timesheet['created_by'] != $employee['id'] && !$this->isAdmin()) {
            Session::flash('error', 'Access denied');
            $this->redirect('/timesheets');
            return;
        }
        
        $this->view('timesheets.show', [
            'timesheet' => $timesheet
        ]);
    }
}

