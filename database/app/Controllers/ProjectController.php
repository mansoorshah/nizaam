<?php

class ProjectController extends Controller
{
    private $projectModel;
    private $employeeModel;
    private $auditLog;

    public function __construct()
    {
        parent::__construct();
        $this->projectModel = new Project();
        $this->employeeModel = new Employee();
        $this->auditLog = new AuditLog();
    }

    public function index()
    {
        $filters = ['status' => Request::input('status', 'active')];
        $projects = $this->projectModel->getAllWithOwners($filters);

        $this->view('projects.index', [
            'projects' => $projects,
            'filters' => $filters
        ]);
    }

    public function show($id)
    {
        $project = $this->projectModel->getWithOwner($id);
        if (!$project) {
            $this->redirect('/projects');
            return;
        }

        $members = $this->projectModel->getMembers($id);
        $workItems = (new WorkItem())->getAll(['project_id' => $id]);

        $this->view('projects.show', [
            'project' => $project,
            'members' => $members,
            'workItems' => $workItems
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo "403 - Access Denied";
            return;
        }

        $employees = $this->employeeModel->getAllWithUsers(['employment_status' => 'active']);
        
        if (empty($employees)) {
            $employees = $this->employeeModel->getAll();
        }
        
        $this->view('projects.create', ['employees' => $employees]);
    }

    public function store()
    {
        if (!Request::isPost()) {
            $this->redirect('/projects/create');
            return;
        }

        $employee = $this->getCurrentEmployee();
        
        $projectId = $this->projectModel->create([
            'name' => Request::input('name'),
            'description' => Request::input('description'),
            'owner_id' => Request::input('owner_id', $employee['id']),
            'status' => 'active'
        ]);

        $this->auditLog->log(
            $this->getCurrentUser()['id'],
            'create',
            'project',
            $projectId
        );

        Session::flash('success', 'Project created successfully');
        $this->redirect('/projects/' . $projectId);
    }

    public function edit($id)
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo "403 - Access Denied";
            return;
        }

        $project = $this->projectModel->getWithOwner($id);
        if (!$project) {
            $this->redirect('/projects');
            return;
        }

        $employees = $this->employeeModel->getAllWithUsers(['employment_status' => 'active']);
        if (empty($employees)) {
            $employees = $this->employeeModel->getAll();
        }

        $this->view('projects.edit', [
            'project' => $project,
            'employees' => $employees
        ]);
    }

    public function update($id)
    {
        if (!Request::isPost() || !$this->isAdmin()) {
            $this->redirect('/projects/' . $id);
            return;
        }

        $this->projectModel->update($id, [
            'name' => Request::input('name'),
            'description' => Request::input('description'),
            'owner_id' => Request::input('owner_id'),
            'status' => Request::input('status')
        ]);

        $this->auditLog->log(
            $this->getCurrentUser()['id'],
            'update',
            'project',
            $id
        );

        Session::flash('success', 'Project updated successfully');
        $this->redirect('/projects/' . $id);
    }

    public function addMember($id)
    {
        if (!Request::isPost()) {
            $this->redirect('/projects/' . $id);
            return;
        }

        $employeeId = Request::input('employee_id');
        $this->projectModel->addMember($id, $employeeId);

        Session::flash('success', 'Member added');
        $this->redirect('/projects/' . $id);
    }
}
