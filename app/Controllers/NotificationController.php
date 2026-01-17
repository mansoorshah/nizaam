<?php

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $this->notificationModel = new Notification();
    }

    public function index()
    {
        $employee = $this->getCurrentEmployee();
        $notifications = $this->notificationModel->getForEmployee($employee['id']);
        $unreadCount = $this->notificationModel->getUnreadCount($employee['id']);

        $this->view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $this->notificationModel->markAsRead($id);
        $this->redirect('/notifications');
    }

    public function markAllAsRead()
    {
        $employee = $this->getCurrentEmployee();
        $this->notificationModel->markAllAsRead($employee['id']);
        $this->redirect('/notifications');
    }
}
