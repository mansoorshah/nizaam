<?php

class EmployeeApiController extends ApiController
{
    private $employeeModel;

    public function __construct()
    {
        parent::__construct();
        $this->employeeModel = new Employee();
    }

    // GET /api/employees
    public function index()
    {
        $this->validateApiRequest();
        
        $filters = [
            'department' => Request::input('department'),
            'employment_status' => Request::input('status')
        ];
        
        $employees = $this->employeeModel->getAllWithUsers($filters);
        $this->successResponse($employees);
    }

    // GET /api/employees/{id}
    public function show($id)
    {
        $this->validateApiRequest();
        
        $employee = $this->employeeModel->getWithUser($id);
        if (!$employee) {
            $this->errorResponse('Employee not found', 404);
        }

        $this->successResponse($employee);
    }

    // POST /api/employees
    public function store()
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        // Validate required fields
        $required = ['email', 'password', 'full_name', 'employee_id', 'designation', 'department', 'join_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->errorResponse("Missing required field: $field");
            }
        }

        try {
            // Start transaction
            $this->db->beginTransaction();
            
            // Create user
            $userId = $this->db->insert('users', [
                'email' => $data['email'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'role' => $data['role'] ?? 'user',
                'is_active' => true
            ]);

            // Create employee
            $employeeId = $this->employeeModel->create([
                'user_id' => $userId,
                'employee_id' => $data['employee_id'],
                'full_name' => $data['full_name'],
                'designation' => $data['designation'],
                'department' => $data['department'],
                'manager_id' => $data['manager_id'] ?? null,
                'employment_status' => $data['employment_status'] ?? 'active',
                'join_date' => $data['join_date']
            ]);

            // Initialize leave balances (CRITICAL FIX)
            $leaveService = new LeaveService();
            $leaveService->initializeLeaveBalance($employeeId, $data['designation']);
            
            // Commit transaction
            $this->db->commit();

            $employee = $this->employeeModel->getWithUser($employeeId);
            $this->successResponse($employee, 'Employee created successfully');
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollback();
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // PUT /api/employees/{id}
    public function update($id)
    {
        $this->validateApiRequest();
        
        $employee = $this->employeeModel->find($id);
        if (!$employee) {
            $this->errorResponse('Employee not found', 404);
        }

        $data = $this->getJsonInput();

        try {
            $updateData = [];
            if (isset($data['full_name'])) $updateData['full_name'] = $data['full_name'];
            if (isset($data['designation'])) $updateData['designation'] = $data['designation'];
            if (isset($data['department'])) $updateData['department'] = $data['department'];
            if (isset($data['manager_id'])) $updateData['manager_id'] = $data['manager_id'];
            if (isset($data['employment_status'])) $updateData['employment_status'] = $data['employment_status'];
            if (isset($data['exit_date'])) $updateData['exit_date'] = $data['exit_date'];

            $this->employeeModel->update($id, $updateData);
            
            $updated = $this->employeeModel->getWithUser($id);
            $this->successResponse($updated, 'Employee updated successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // DELETE /api/employees/{id}
    public function delete($id)
    {
        $this->validateApiRequest();
        
        $employee = $this->employeeModel->find($id);
        if (!$employee) {
            $this->errorResponse('Employee not found', 404);
        }

        try {
            // This will cascade delete the user as well
            $this->employeeModel->delete($id);
            $this->successResponse(null, 'Employee deleted successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
