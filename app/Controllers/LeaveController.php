<?php

class LeaveController extends Controller
{
    private $leaveService;
    private $workItemModel;

    public function __construct()
    {
        parent::__construct();
        $this->leaveService = new LeaveService();
        $this->workItemModel = new WorkItem();
    }

    public function index()
    {
        $employee = $this->getCurrentEmployee();
        
        $leaveBalances = $this->leaveService->getLeaveBalance($employee['id']);
        $leaveRequests = $this->workItemModel->getAll([
            'type' => 'leave_request',
            'created_by' => $employee['id']
        ]);

        $this->view('leaves.index', [
            'leaveBalances' => $leaveBalances,
            'leaveRequests' => $leaveRequests
        ]);
    }

    public function create()
    {
        $employee = $this->getCurrentEmployee();
        $leaveTypes = $this->leaveService->getLeaveTypes();
        $leaveBalances = $this->leaveService->getLeaveBalance($employee['id']);

        $this->view('leaves.create', [
            'leaveTypes' => $leaveTypes,
            'leaveBalances' => $leaveBalances
        ]);
    }

    public function store()
    {
        if (!Request::isPost()) {
            $this->redirect('/leaves/request');
            return;
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            $workItemId = $this->leaveService->submitLeaveRequest(
                $employee['id'],
                Request::input('leave_type_id'),
                Request::input('start_date'),
                Request::input('end_date'),
                Request::input('reason')
            );

            Session::flash('success', 'Leave request submitted successfully');
            $this->redirect('/work-items/' . $workItemId);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/leaves/request');
        }
    }

    public function show($id)
    {
        $workItem = $this->workItemModel->getWithDetails($id);
        $this->redirect('/work-items/' . $id);
    }
}
