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

        // CSRF Protection (skip for now to avoid breaking existing functionality)
        // TODO: Uncomment when all forms have CSRF tokens
        // Request::validateCsrf();

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

    public function calendar()
    {
        $employee = $this->getCurrentEmployee();
        
        // Get leave types with colors
        $leaveTypes = $this->db->fetchAll("SELECT * FROM leave_types");
        
        // Create color map for leave types
        $typeColors = [
            'Annual Leave' => '#3B82F6',
            'Sick Leave' => '#EF4444',
            'Unpaid Leave' => '#6B7280',
            'Emergency Leave' => '#F59E0B'
        ];
        
        foreach ($leaveTypes as &$type) {
            $type['color'] = $typeColors[$type['name']] ?? '#3B82F6';
        }
        
        // Get leave requests for calendar
        // If admin, show all leaves, otherwise only user's leaves
        if ($this->isAdmin()) {
            $sql = "SELECT wi.id, wi.title, wi.metadata, ws.color, wi.type
                    FROM work_items wi
                    INNER JOIN workflow_statuses ws ON wi.current_status_id = ws.id
                    WHERE wi.type = 'leave_request'
                    ORDER BY wi.created_at DESC";
            $leaves = $this->db->fetchAll($sql);
        } else {
            $sql = "SELECT wi.id, wi.title, wi.metadata, ws.color, wi.type
                    FROM work_items wi
                    INNER JOIN workflow_statuses ws ON wi.current_status_id = ws.id
                    WHERE wi.type = 'leave_request' AND wi.created_by = ?
                    ORDER BY wi.created_at DESC";
            $leaves = $this->db->fetchAll($sql, [$employee['id']]);
        }
        
        // Format events for FullCalendar
        $calendarEvents = [];
        foreach ($leaves as $leave) {
            $metadata = !empty($leave['metadata']) ? json_decode($leave['metadata'], true) : [];
            if (isset($metadata['start_date']) && isset($metadata['end_date'])) {
                // Add one day to end_date for FullCalendar (exclusive end)
                $endDate = date('Y-m-d', strtotime($metadata['end_date'] . ' +1 day'));
                
                $calendarEvents[] = [
                    'id' => $leave['id'],
                    'title' => $leave['title'],
                    'start' => $metadata['start_date'],
                    'end' => $endDate,
                    'backgroundColor' => $leave['color'],
                    'borderColor' => $leave['color'],
                    'allDay' => true
                ];
            }
        }
        
        $this->view('leaves.calendar', [
            'calendarEvents' => $calendarEvents,
            'leaveTypes' => $leaveTypes
        ]);
    }
}
