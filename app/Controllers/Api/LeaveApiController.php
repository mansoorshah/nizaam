<?php

class LeaveApiController extends ApiController
{
    private $workItemModel;
    private $workflowService;
    private $leaveTypeModel;

    public function __construct()
    {
        parent::__construct();
        $this->workItemModel = new WorkItem();
        $this->workflowService = new WorkflowService();
        $this->leaveTypeModel = new LeaveType();
    }

    // GET /api/leaves
    public function index()
    {
        $this->validateApiRequest();
        
        $leaves = $this->workItemModel->getAll(['type' => 'leave_request']);
        $this->successResponse($leaves);
    }

    // GET /api/leaves/{id}
    public function show($id)
    {
        $this->validateApiRequest();
        
        $leave = $this->workItemModel->getWithDetails($id);
        if (!$leave || $leave['type'] !== 'leave_request') {
            $this->errorResponse('Leave request not found', 404);
        }

        $this->successResponse($leave);
    }

    // POST /api/leaves
    public function store()
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        if (empty($data['leave_type_id']) || empty($data['start_date']) || empty($data['end_date']) || empty($data['reason'])) {
            $this->errorResponse('Missing required fields: leave_type_id, start_date, end_date, reason');
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            // Use LeaveService to handle leave request creation with proper validation
            require_once __DIR__ . '/../../Services/LeaveService.php';
            $leaveService = new LeaveService();
            
            $workItemId = $leaveService->submitLeaveRequest(
                $employee['id'],
                $data['leave_type_id'],
                $data['start_date'],
                $data['end_date'],
                $data['reason']
            );

            $leave = $this->workItemModel->getWithDetails($workItemId);
            $this->successResponse($leave, 'Leave request created successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // GET /api/leave-types
    public function leaveTypes()
    {
        $this->validateApiRequest();
        
        $types = $this->leaveTypeModel->getAll();
        $this->successResponse($types);
    }

    // GET /api/leave-balance
    public function balance()
    {
        $this->validateApiRequest();
        
        $employee = $this->getCurrentEmployee();
        $balances = $this->leaveTypeModel->getBalanceForEmployee($employee['id']);
        $this->successResponse($balances);
    }
}
