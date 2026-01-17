<?php

class DashboardController extends Controller
{
    private $workItemModel;
    private $notificationModel;
    private $projectModel;

    public function __construct()
    {
        parent::__construct();
        $this->workItemModel = new WorkItem();
        $this->notificationModel = new Notification();
        $this->projectModel = new Project();
    }

    public function index()
    {
        $employee = $this->getCurrentEmployee();
        
        // Handle case where employee profile doesn't exist yet
        if (!$employee) {
            $this->view('dashboard.no_employee');
            return;
        }
        
        // Get work items assigned to current user
        $myWorkItems = $this->workItemModel->getAll(['assigned_to' => $employee['id']]);
        
        // Get work items created by current user
        $createdByMe = $this->workItemModel->getAll(['created_by' => $employee['id']]);
        
        // Get recent notifications
        $notifications = $this->notificationModel->getForEmployee($employee['id'], 10);
        
        // Get unread notification count
        $unreadCount = $this->notificationModel->getUnreadCount($employee['id']);

        // Get projects (if admin or owner)
        $projects = [];
        if ($this->isAdmin()) {
            $projects = $this->projectModel->getAllWithOwners(['status' => 'active']);
        }

        $this->view('dashboard.index', [
            'myWorkItems' => $myWorkItems,
            'createdByMe' => $createdByMe,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'projects' => $projects
        ]);
    }
}
