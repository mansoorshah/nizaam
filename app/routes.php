<?php

// Start session
Session::start();

// Initialize router
$router = new Router();

// Guest routes
$router->get('/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Authenticated routes
$router->get('/', [DashboardController::class, 'index'], [AuthMiddleware::class]);
$router->get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);

// Employee routes
$router->get('/employees', [EmployeeController::class, 'index'], [AuthMiddleware::class]);
$router->get('/employees/create', [EmployeeController::class, 'create'], [AdminMiddleware::class]);
$router->post('/employees/create', [EmployeeController::class, 'store'], [AdminMiddleware::class]);
$router->get('/employees/{id}', [EmployeeController::class, 'show'], [AuthMiddleware::class]);
$router->get('/employees/{id}/edit', [EmployeeController::class, 'edit'], [AuthMiddleware::class]);
$router->post('/employees/{id}/edit', [EmployeeController::class, 'update'], [AuthMiddleware::class]);

// Work item routes
$router->get('/work-items', [WorkItemController::class, 'index'], [AuthMiddleware::class]);
$router->get('/work-items/create', [WorkItemController::class, 'create'], [AuthMiddleware::class]);
$router->post('/work-items/create', [WorkItemController::class, 'store'], [AuthMiddleware::class]);
$router->get('/work-items/{id}', [WorkItemController::class, 'show'], [AuthMiddleware::class]);
$router->post('/work-items/{id}/status', [WorkItemController::class, 'updateStatus'], [AuthMiddleware::class]);
$router->post('/work-items/{id}/comment', [WorkItemController::class, 'addComment'], [AuthMiddleware::class]);

// Project routes
$router->get('/projects', [ProjectController::class, 'index'], [AuthMiddleware::class]);
$router->get('/projects/create', [ProjectController::class, 'create'], [AdminMiddleware::class]);
$router->post('/projects/store', [ProjectController::class, 'store'], [AdminMiddleware::class]);
$router->get('/projects/{id}', [ProjectController::class, 'show'], [AuthMiddleware::class]);
$router->post('/projects/{id}/members', [ProjectController::class, 'addMember'], [AuthMiddleware::class]);

// Leave routes
$router->get('/leaves', [LeaveController::class, 'index'], [AuthMiddleware::class]);
$router->get('/leaves/request', [LeaveController::class, 'create'], [AuthMiddleware::class]);
$router->post('/leaves/request', [LeaveController::class, 'store'], [AuthMiddleware::class]);
$router->get('/leaves/{id}', [LeaveController::class, 'show'], [AuthMiddleware::class]);

// Notification routes
$router->get('/notifications', [NotificationController::class, 'index'], [AuthMiddleware::class]);
$router->post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'], [AuthMiddleware::class]);
$router->post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'], [AuthMiddleware::class]);

// Admin routes
$router->get('/reports', [ReportController::class, 'index'], [AdminMiddleware::class]);
$router->get('/audit', [AuditController::class, 'index'], [AdminMiddleware::class]);

// Expense routes
$router->get('/expenses/create', [ExpenseController::class, 'create'], [AuthMiddleware::class]);
$router->post('/expenses/create', [ExpenseController::class, 'store'], [AuthMiddleware::class]);

// Timesheet routes
$router->get('/timesheets/create', [TimesheetController::class, 'create'], [AuthMiddleware::class]);
$router->post('/timesheets/create', [TimesheetController::class, 'store'], [AuthMiddleware::class]);

// Dispatch
$router->dispatch();
