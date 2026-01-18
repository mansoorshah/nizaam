<?php

class ExpenseApiController extends ApiController
{
    private $workItemModel;
    private $workflowService;

    public function __construct()
    {
        parent::__construct();
        $this->workItemModel = new WorkItem();
        $this->workflowService = new WorkflowService();
    }

    // GET /api/expenses
    public function index()
    {
        $this->validateApiRequest();
        
        $expenses = $this->workItemModel->getAll(['type' => 'expense']);
        $this->successResponse($expenses);
    }

    // GET /api/expenses/{id}
    public function show($id)
    {
        $this->validateApiRequest();
        
        $expense = $this->workItemModel->getWithDetails($id);
        if (!$expense || $expense['type'] !== 'expense') {
            $this->errorResponse('Expense not found', 404);
        }

        $this->successResponse($expense);
    }

    // POST /api/expenses
    public function store()
    {
        $this->validateApiRequest();
        
        $data = $this->getJsonInput();
        
        if (empty($data['amount']) || empty($data['category']) || empty($data['date']) || empty($data['description'])) {
            $this->errorResponse('Missing required fields: amount, category, date, description');
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            $metadata = [
                'amount' => $data['amount'],
                'category' => $data['category'],
                'date' => $data['date']
            ];

            $workItemId = $this->workflowService->createWorkItem(
                'expense',
                'Expense: ' . $data['category'] . ' - $' . $data['amount'],
                $data['description'],
                $employee['id'],
                $employee['manager_id'] ?? null,
                'medium',
                null,
                null,
                $metadata
            );

            $expense = $this->workItemModel->getWithDetails($workItemId);
            $this->successResponse($expense, 'Expense created successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }

    // DELETE /api/expenses/{id}
    public function delete($id)
    {
        $this->validateApiRequest();
        
        $expense = $this->workItemModel->find($id);
        if (!$expense || $expense['type'] !== 'expense') {
            $this->errorResponse('Expense not found', 404);
        }

        try {
            $this->workItemModel->delete($id);
            $this->successResponse(null, 'Expense deleted successfully');
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage(), 500);
        }
    }
}
