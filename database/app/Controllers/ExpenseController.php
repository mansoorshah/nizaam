<?php

class ExpenseController extends Controller
{
    private $workflowService;

    public function __construct()
    {
        parent::__construct();
        $this->workflowService = new WorkflowService();
    }

    public function create()
    {
        $this->view('expenses.create');
    }

    public function store()
    {
        if (!Request::isPost()) {
            $this->redirect('/expenses/create');
            return;
        }

        try {
            $employee = $this->getCurrentEmployee();
            
            $metadata = [
                'amount' => Request::input('amount'),
                'category' => Request::input('category'),
                'date' => Request::input('date')
            ];

            $workItemId = $this->workflowService->createWorkItem(
                'expense',
                'Expense: ' . Request::input('category') . ' - $' . Request::input('amount'),
                Request::input('description'),
                $employee['id'],
                null,
                'medium',
                null,
                null,
                $metadata
            );

            Session::flash('success', 'Expense submitted successfully');
            $this->redirect('/work-items/' . $workItemId);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            $this->redirect('/expenses/create');
        }
    }
}
