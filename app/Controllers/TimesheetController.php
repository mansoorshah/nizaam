<?php

class TimesheetController extends Controller
{
    private $workflowService;

    public function __construct()
    {
        parent::__construct();
        $this->workflowService = new WorkflowService();
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
            
            $metadata = [
                'week_ending' => Request::input('week_ending'),
                'hours' => Request::input('hours')
            ];

            $totalHours = array_sum($metadata['hours']);
            
            $workItemId = $this->workflowService->createWorkItem(
                'timesheet',
                'Timesheet: Week ending ' . Request::input('week_ending') . ' (' . $totalHours . ' hours)',
                Request::input('notes'),
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
}
