<?php

class WorkItemApiController extends ApiController
{
    private $workItemModel;
    private $workflowService;

    public function __construct()
    {
        parent::__construct();
        $this->workItemModel = new WorkItem();
        $this->workflowService = new WorkflowService();
    }

    // GET /api/work-items
    public function index()
    {
        $this->validateApiRequest();
        
        $filters = [
            'type' => Request::input('type'),
            'project_id' => Request::input('project_id'),
            'priority' => Request::input('priority'),
            'status_id' => Request::input('status_id')
        ];

        $workItems = $this->workItemModel->getAll($filters);
        $this->successResponse($workItems);
    }

    // GET /api/work-items/{id}
    public function show($id)
    {
        $this->validateApiRequest();
        
        $workItem = $this->workItemModel->getWithDetails($id);
        if (!$workItem) {
            $this->errorResponse('Work item not found', 404);
        }

        $this->successResponse($workItem);
    }

    // POST /api/work-items
    public function store()
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        // Validate required fields
        if (empty($data['title']) || empty($data['type']) || empty($data['priority'])) {
            $this->errorResponse('Missing required fields: title, type, priority');
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            $workItemId = $this->workflowService->createWorkItem(
                $data['type'],
                $data['title'],
                $data['description'] ?? null,
                $employee['id'],
                $data['assigned_to'] ?? null,
                $data['priority'],
                $data['due_date'] ?? null,
                $data['project_id'] ?? null
            );

            $workItem = $this->workItemModel->getWithDetails($workItemId);
            $this->successResponse($workItem, 'Work item created successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // PUT /api/work-items/{id}
    public function update($id)
    {
        $this->validateApiRequest();
        
        $workItem = $this->workItemModel->find($id);
        if (!$workItem) {
            $this->errorResponse('Work item not found', 404);
        }

        $data = $this->getJsonInput();

        try {
            $updateData = [];
            if (isset($data['title'])) $updateData['title'] = $data['title'];
            if (isset($data['description'])) $updateData['description'] = $data['description'];
            if (isset($data['assigned_to'])) $updateData['assigned_to'] = $data['assigned_to'];
            if (isset($data['priority'])) $updateData['priority'] = $data['priority'];
            if (isset($data['due_date'])) $updateData['due_date'] = $data['due_date'];
            if (isset($data['project_id'])) $updateData['project_id'] = $data['project_id'];

            $this->workItemModel->update($id, $updateData);
            
            $updated = $this->workItemModel->getWithDetails($id);
            $this->successResponse($updated, 'Work item updated successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // DELETE /api/work-items/{id}
    public function delete($id)
    {
        $this->validateApiRequest();
        
        $workItem = $this->workItemModel->find($id);
        if (!$workItem) {
            $this->errorResponse('Work item not found', 404);
        }

        try {
            $this->workItemModel->delete($id);
            $this->successResponse(null, 'Work item deleted successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // POST /api/work-items/{id}/status
    public function updateStatus($id)
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        if (empty($data['to_status_id'])) {
            $this->errorResponse('Missing required field: to_status_id');
        }

        try {
            $employee = $this->getCurrentEmployee();
            $this->workflowService->transitionWorkItem(
                $id,
                $data['to_status_id'],
                $employee['id'],
                $data['comment'] ?? null
            );
            
            $workItem = $this->workItemModel->getWithDetails($id);
            $this->successResponse($workItem, 'Status updated successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // POST /api/work-items/{id}/comments
    public function addComment($id)
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        if (empty($data['comment'])) {
            $this->errorResponse('Missing required field: comment');
        }

        try {
            $employee = $this->getCurrentEmployee();
            $commentId = $this->workItemModel->addComment($id, $employee['id'], $data['comment']);
            
            $this->successResponse(['comment_id' => $commentId], 'Comment added successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
