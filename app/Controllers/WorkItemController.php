<?php

class WorkItemController extends Controller
{
    private $workItemModel;
    private $workflowService;
    private $projectModel;
    private $employeeModel;
    private $auditLog;

    public function __construct()
    {
        parent::__construct();
        $this->workItemModel = new WorkItem();
        $this->workflowService = new WorkflowService();
        $this->projectModel = new Project();
        $this->employeeModel = new Employee();
        $this->auditLog = new AuditLog();
    }

    public function index()
    {
        $filters = [
            'type' => Request::input('type'),
            'project_id' => Request::input('project_id'),
            'priority' => Request::input('priority')
        ];

        // Non-admins only see their work items
        if (!$this->isAdmin()) {
            $employee = $this->getCurrentEmployee();
            $filters['assigned_to'] = $employee['id'];
        }

        $workItems = $this->workItemModel->getAll($filters);
        $projects = $this->projectModel->getAllWithOwners(['status' => 'active']);

        $this->view('work_items.index', [
            'workItems' => $workItems,
            'projects' => $projects,
            'filters' => $filters
        ]);
    }

    public function show($id)
    {
        $workItem = $this->workItemModel->getWithDetails($id);
        
        if (!$workItem) {
            $this->redirect('/work-items');
            return;
        }

        $history = $this->workItemModel->getHistory($id);
        $transitions = $this->workflowService->getAvailableTransitions($id);
        
        // Get comments and attachments
        $comments = $this->db->fetchAll(
            "SELECT c.*, e.full_name as author_name 
             FROM comments c 
             INNER JOIN employees e ON c.employee_id = e.id 
             WHERE c.work_item_id = ? 
             ORDER BY c.created_at DESC",
            [$id]
        );
        
        $attachments = $this->db->fetchAll(
            "SELECT a.*, e.full_name as uploaded_by_name 
             FROM attachments a 
             INNER JOIN employees e ON a.uploaded_by = e.id 
             WHERE a.work_item_id = ? 
             ORDER BY a.created_at DESC",
            [$id]
        );

        $this->view('work_items.show', [
            'workItem' => $workItem,
            'history' => $history,
            'transitions' => $transitions,
            'comments' => $comments,
            'attachments' => $attachments
        ]);
    }

    public function create()
    {
        $projects = $this->projectModel->getAllWithOwners(['status' => 'active']);
        $employees = $this->employeeModel->getAllWithUsers(['status' => 'active']);

        $this->view('work_items.create', [
            'projects' => $projects,
            'employees' => $employees
        ]);
    }

    public function store()
    {
        if (!Request::isPost()) {
            $this->redirect('/work-items/create');
            return;
        }

        $validation = Request::validate([
            'title' => 'required|max:255',
            'type' => 'required',
            'priority' => 'required'
        ]);

        if ($validation !== true) {
            Session::flash('errors', $validation);
            Session::flash('old', Request::all());
            $this->redirect('/work-items/create');
            return;
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            $workItemId = $this->workflowService->createWorkItem(
                Request::input('type'),
                Request::input('title'),
                Request::input('description'),
                $employee['id'],
                Request::input('assigned_to') ?: null,
                Request::input('priority'),
                Request::input('due_date') ?: null,
                Request::input('project_id') ?: null
            );

            Session::flash('success', 'Work item created successfully');
            $this->redirect('/work-items/' . $workItemId);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/work-items/create');
        }
    }

    public function updateStatus($id)
    {
        if (!Request::isPost()) {
            $this->redirect('/work-items/' . $id);
            return;
        }

        $toStatusId = Request::input('to_status_id');
        $comment = Request::input('comment');

        try {
            $employee = $this->getCurrentEmployee();
            $this->workflowService->transitionWorkItem($id, $toStatusId, $employee['id'], $comment);
            
            Session::flash('success', 'Status updated successfully');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        $this->redirect('/work-items/' . $id);
    }

    public function addComment($id)
    {
        if (!Request::isPost()) {
            $this->redirect('/work-items/' . $id);
            return;
        }

        $comment = Request::input('comment');
        if (empty($comment)) {
            $this->redirect('/work-items/' . $id);
            return;
        }

        $employee = $this->getCurrentEmployee();
        
        $this->db->execute(
            "INSERT INTO comments (work_item_id, employee_id, comment) VALUES (?, ?, ?)",
            [$id, $employee['id'], $comment]
        );

        // Audit log
        $this->auditLog->log(
            $this->getCurrentUser()['id'],
            'add_comment',
            'work_item',
            $id
        );

        Session::flash('success', 'Comment added');
        $this->redirect('/work-items/' . $id);
    }
}
