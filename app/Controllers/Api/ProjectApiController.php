<?php

class ProjectApiController extends ApiController
{
    private $projectModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectModel = new Project();
    }

    // GET /api/projects
    public function index()
    {
        $this->validateApiRequest();
        
        $filters = ['status' => Request::input('status')];
        $projects = $this->projectModel->getAllWithOwners($filters);
        $this->successResponse($projects);
    }

    // GET /api/projects/{id}
    public function show($id)
    {
        $this->validateApiRequest();
        
        $project = $this->projectModel->getWithDetails($id);
        if (!$project) {
            $this->errorResponse('Project not found', 404);
        }

        $this->successResponse($project);
    }

    // POST /api/projects
    public function store()
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        if (empty($data['name'])) {
            $this->errorResponse('Missing required field: name', 400);
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            $projectData = [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'owner_id' => $data['owner_id'] ?? $employee['id'],
                'status' => $data['status'] ?? 'active'
            ];
            
            // Add optional fields if provided
            if (isset($data['start_date'])) {
                $projectData['start_date'] = $data['start_date'];
            }
            if (isset($data['end_date'])) {
                $projectData['end_date'] = $data['end_date'];
            }
            if (isset($data['budget'])) {
                $projectData['budget'] = $data['budget'];
            }
            
            $projectId = $this->projectModel->create($projectData);

            $project = $this->projectModel->getWithDetails($projectId);
            $this->successResponse($project, 'Project created successfully');
        } catch (Exception $e) {
            error_log("Project creation error: " . $e->getMessage());
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // PUT /api/projects/{id}
    public function update($id)
    {
        $this->validateApiRequest();
        
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->errorResponse('Project not found', 404);
        }

        $data = $this->getJsonInput();

        try {
            $updateData = [];
            if (isset($data['name'])) $updateData['name'] = $data['name'];
            if (isset($data['description'])) $updateData['description'] = $data['description'];
            if (isset($data['owner_id'])) $updateData['owner_id'] = $data['owner_id'];
            if (isset($data['status'])) $updateData['status'] = $data['status'];

            $this->projectModel->update($id, $updateData);
            
            $updated = $this->projectModel->getWithDetails($id);
            $this->successResponse($updated, 'Project updated successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // DELETE /api/projects/{id}
    public function delete($id)
    {
        $this->validateApiRequest();
        
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->errorResponse('Project not found', 404);
        }

        try {
            $this->projectModel->delete($id);
            $this->successResponse(null, 'Project deleted successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // POST /api/projects/{id}/members
    public function addMember($id)
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        if (empty($data['employee_id'])) {
            $this->errorResponse('Missing required field: employee_id');
        }

        try {
            $this->projectModel->addMember($id, $data['employee_id']);
            $this->successResponse(null, 'Member added successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // DELETE /api/projects/{id}/members/{employeeId}
    public function removeMember($id, $employeeId)
    {
        $this->validateApiRequest();
        
        try {
            $this->projectModel->removeMember($id, $employeeId);
            $this->successResponse(null, 'Member removed successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
