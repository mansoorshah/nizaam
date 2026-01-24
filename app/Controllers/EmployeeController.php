<?php

class EmployeeController extends Controller
{
    private $employeeModel;
    private $userModel;
    private $auditLog;
    private $leaveService;

    public function __construct()
    {
        parent::__construct();
        $this->employeeModel = new Employee();
        $this->userModel = new User();
        $this->auditLog = new AuditLog();
        $this->leaveService = new LeaveService();
    }

    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect('/employees/' . $this->getCurrentEmployee()['id']);
            return;
        }

        $filters = [
            'search' => Request::input('search'),
            'department' => Request::input('department'),
            'status' => Request::input('status')
        ];

        $employees = $this->employeeModel->getAllWithUsers($filters);
        $departments = $this->employeeModel->getDepartments();

        $this->view('employees.index', [
            'employees' => $employees,
            'departments' => $departments,
            'filters' => $filters
        ]);
    }

    public function show($id)
    {
        $employee = $this->employeeModel->findWithUser($id);
        
        if (!$employee) {
            $this->redirect('/employees');
            return;
        }

        $currentEmployee = $this->getCurrentEmployee();
        
        // Check access: admin or viewing own profile
        if (!$this->isAdmin() && $currentEmployee['id'] != $id) {
            http_response_code(403);
            echo "403 - Access Denied";
            return;
        }

        $manager = $employee['manager_id'] ? $this->employeeModel->find($employee['manager_id']) : null;
        $directReports = $this->employeeModel->getDirectReports($id);
        $leaveBalances = $this->leaveService->getLeaveBalance($id);

        $this->view('employees.show', [
            'employee' => $employee,
            'manager' => $manager,
            'directReports' => $directReports,
            'leaveBalances' => $leaveBalances
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            http_response_code(403);
            return;
        }

        $managers = $this->employeeModel->getAllWithUsers();
        $departments = $this->employeeModel->getDepartments();

        $this->view('employees.create', [
            'managers' => $managers,
            'departments' => $departments
        ]);
    }

    public function store()
    {
        if (!$this->isAdmin() || !Request::isPost()) {
            http_response_code(403);
            return;
        }

        // CSRF Protection (skip for now to avoid breaking existing functionality)
        // TODO: Uncomment when all forms have CSRF tokens
        // Request::validateCsrf();

        $validation = Request::validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'full_name' => 'required',
            'employee_id' => 'required',
            'designation' => 'required',
            'department' => 'required',
            'join_date' => 'required'
        ]);

        if ($validation !== true) {
            Session::flash('errors', $validation);
            Session::flash('old', Request::all());
            $this->redirect('/employees/create');
            return;
        }

        try {
            $this->db->beginTransaction();

            // Create user
            $userId = $this->userModel->createUser(
                Request::input('email'),
                Request::input('password'),
                Request::input('role', 'user')
            );

            // Create employee
            $employeeId = $this->employeeModel->create([
                'user_id' => $userId,
                'employee_id' => Request::input('employee_id'),
                'full_name' => Request::input('full_name'),
                'designation' => Request::input('designation'),
                'department' => Request::input('department'),
                'manager_id' => Request::input('manager_id') ?: null,
                'employment_status' => 'active',
                'join_date' => Request::input('join_date')
            ]);

            // Initialize leave balances
            $this->leaveService->initializeLeaveBalance($employeeId, Request::input('designation'));

            $this->db->commit();

            // Audit log
            $this->auditLog->log(
                $this->getCurrentUser()['id'],
                'create',
                'employee',
                $employeeId,
                ['email' => Request::input('email')]
            );

            Session::flash('success', 'Employee created successfully');
            $this->redirect('/employees/' . $employeeId);
        } catch (Exception $e) {
            $this->db->rollback();
            Session::flash('error', 'Failed to create employee: ' . $e->getMessage());
            $this->redirect('/employees/create');
        }
    }

    public function edit($id)
    {
        $employee = $this->employeeModel->findWithUser($id);
        $currentEmployee = $this->getCurrentEmployee();
        
        // Check access
        if (!$this->isAdmin() && $currentEmployee['id'] != $id) {
            http_response_code(403);
            return;
        }

        $managers = $this->employeeModel->getAllWithUsers();
        $departments = $this->employeeModel->getDepartments();

        $this->view('employees.edit', [
            'employee' => $employee,
            'managers' => $managers,
            'departments' => $departments
        ]);
    }

    public function update($id)
    {
        if (!Request::isPost()) {
            $this->redirect('/employees/' . $id);
            return;
        }

        $currentEmployee = $this->getCurrentEmployee();
        
        // Check access
        if (!$this->isAdmin() && $currentEmployee['id'] != $id) {
            http_response_code(403);
            return;
        }

        $updateData = [];
        
        // Users can only update certain fields
        if ($this->isAdmin()) {
            $updateData = [
                'full_name' => Request::input('full_name'),
                'designation' => Request::input('designation'),
                'department' => Request::input('department'),
                'manager_id' => Request::input('manager_id') ?: null,
                'employment_status' => Request::input('employment_status')
            ];
        } else {
            $updateData = [
                'full_name' => Request::input('full_name')
            ];
        }

        $this->employeeModel->update($id, $updateData);

        // Audit log
        $this->auditLog->log(
            $this->getCurrentUser()['id'],
            'update',
            'employee',
            $id,
            $updateData
        );

        Session::flash('success', 'Profile updated successfully');
        $this->redirect('/employees/' . $id);
    }
}
