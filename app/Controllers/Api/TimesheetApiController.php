<?php

class TimesheetApiController extends ApiController
{
    private $workItemModel;
    private $workflowService;

    public function __construct()
    {
        parent::__construct();
        $this->workItemModel = new WorkItem();
        $this->workflowService = new WorkflowService();
    }

    // GET /api/timesheets
    public function index()
    {
        $this->validateApiRequest();
        
        $timesheets = $this->workItemModel->getAll(['type' => 'timesheet']);
        $this->successResponse($timesheets);
    }

    // GET /api/timesheets/{id}
    public function show($id)
    {
        $this->validateApiRequest();
        
        $timesheet = $this->workItemModel->getWithDetails($id);
        if (!$timesheet || $timesheet['type'] !== 'timesheet') {
            $this->errorResponse('Timesheet not found', 404);
        }

        $this->successResponse($timesheet);
    }

    // POST /api/timesheets
    public function store()
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        if (empty($data['week_ending']) || empty($data['hours'])) {
            $this->errorResponse('Missing required fields: week_ending, hours');
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            $totalHours = array_sum($data['hours']);
            
            $metadata = [
                'week_ending' => $data['week_ending'],
                'hours' => $data['hours'],
                'total_hours' => $totalHours
            ];

            $workItemId = $this->workflowService->createWorkItem(
                'timesheet',
                'Timesheet: Week ending ' . $data['week_ending'] . ' (' . $totalHours . ' hours)',
                $data['notes'] ?? '',
                $employee['id'],
                $employee['manager_id'] ?? null,
                'medium',
                null,
                null,
                $metadata
            );

            $timesheet = $this->workItemModel->getWithDetails($workItemId);
            $this->successResponse($timesheet, 'Timesheet created successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // DELETE /api/timesheets/{id}
    public function delete($id)
    {
        $this->validateApiRequest();
        
        $timesheet = $this->workItemModel->find($id);
        if (!$timesheet || $timesheet['type'] !== 'timesheet') {
            $this->errorResponse('Timesheet not found', 404);
        }

        try {
            $this->workItemModel->delete($id);
            $this->successResponse(null, 'Timesheet deleted successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
