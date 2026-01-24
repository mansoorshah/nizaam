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
$router->post('/work-items/{id}/attachment', [WorkItemController::class, 'uploadAttachment'], [AuthMiddleware::class]);
$router->get('/attachments/{id}/download', [WorkItemController::class, 'downloadAttachment'], [AuthMiddleware::class]);
$router->post('/attachments/{id}/delete', [WorkItemController::class, 'deleteAttachment'], [AuthMiddleware::class]);

// Project routes
$router->get('/projects', [ProjectController::class, 'index'], [AuthMiddleware::class]);
$router->get('/projects/create', [ProjectController::class, 'create'], [AdminMiddleware::class]);
$router->post('/projects/store', [ProjectController::class, 'store'], [AdminMiddleware::class]);
$router->get('/projects/{id}', [ProjectController::class, 'show'], [AuthMiddleware::class]);
$router->get('/projects/{id}/edit', [ProjectController::class, 'edit'], [AdminMiddleware::class]);
$router->post('/projects/{id}/edit', [ProjectController::class, 'update'], [AdminMiddleware::class]);
$router->post('/projects/{id}/members', [ProjectController::class, 'addMember'], [AuthMiddleware::class]);

// Leave routes
$router->get('/leaves', [LeaveController::class, 'index'], [AuthMiddleware::class]);
$router->get('/leaves/calendar', [LeaveController::class, 'calendar'], [AuthMiddleware::class]);
$router->get('/leaves/request', [LeaveController::class, 'create'], [AuthMiddleware::class]);
$router->post('/leaves/request', [LeaveController::class, 'store'], [AuthMiddleware::class]);
$router->get('/leaves/{id}', [LeaveController::class, 'show'], [AuthMiddleware::class]);

// Notification routes
$router->get('/notifications', [NotificationController::class, 'index'], [AuthMiddleware::class]);
$router->post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'], [AuthMiddleware::class]);
$router->post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'], [AuthMiddleware::class]);

// Admin routes
$router->get('/reports', [ReportController::class, 'index'], [AdminMiddleware::class]);
$router->get('/reports/export/work-items', [ReportController::class, 'exportWorkItems'], [AdminMiddleware::class]);
$router->get('/reports/export/workload', [ReportController::class, 'exportEmployeeWorkload'], [AdminMiddleware::class]);
$router->get('/reports/export/leaves', [ReportController::class, 'exportLeaveUsage'], [AdminMiddleware::class]);
$router->get('/audit', [AuditController::class, 'index'], [AdminMiddleware::class]);

// Expense routes
$router->get('/expenses/create', [ExpenseController::class, 'create'], [AuthMiddleware::class]);
$router->post('/expenses/store', [ExpenseController::class, 'store'], [AuthMiddleware::class]);

// Timesheet routes
$router->get('/timesheets', [TimesheetController::class, 'index'], [AuthMiddleware::class]);
$router->get('/timesheets/create', [TimesheetController::class, 'create'], [AuthMiddleware::class]);
$router->post('/timesheets/create', [TimesheetController::class, 'store'], [AuthMiddleware::class]);
$router->get('/timesheets/{id}', [TimesheetController::class, 'show'], [AuthMiddleware::class]);
$router->post('/timesheets/store', [TimesheetController::class, 'store'], [AuthMiddleware::class]);

// ============================================
// REST API ROUTES
// ============================================

// Authentication endpoints (no auth required)
$router->post('/api/auth/login', [ApiAuthController::class, 'login']);
$router->post('/api/auth/logout', [ApiAuthController::class, 'logout']);
$router->post('/api/auth/refresh', [ApiAuthController::class, 'refresh']);
$router->get('/api/auth/me', [ApiAuthController::class, 'me']);

// API Test endpoint (no auth required)
$router->get('/api/test', function() {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'API is working', 'timestamp' => date('Y-m-d H:i:s')]);
    exit;
});

// Debug: Output what routes are registered
if (isset($_GET['debug']) && $_GET['debug'] === 'routes') {
    echo "<pre>";
    echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
    echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";
    echo "Base Path: " . dirname($_SERVER['SCRIPT_NAME']) . "\n";
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    if ($scriptName !== '/') {
        $uri = substr($uri, strlen($scriptName));
    }
    echo "Parsed URI: " . $uri . "\n";
    echo "</pre>";
    exit;
}

// Work Items API
$router->get('/api/work-items', [WorkItemApiController::class, 'index']);
$router->get('/api/work-items/{id}', [WorkItemApiController::class, 'show']);
$router->post('/api/work-items', [WorkItemApiController::class, 'store']);
$router->put('/api/work-items/{id}', [WorkItemApiController::class, 'update']);
$router->delete('/api/work-items/{id}', [WorkItemApiController::class, 'delete']);
$router->post('/api/work-items/{id}/status', [WorkItemApiController::class, 'updateStatus']);
$router->post('/api/work-items/{id}/comments', [WorkItemApiController::class, 'addComment']);

// Projects API
$router->get('/api/projects', [ProjectApiController::class, 'index']);
$router->get('/api/projects/{id}', [ProjectApiController::class, 'show']);
$router->post('/api/projects', [ProjectApiController::class, 'store']);
$router->put('/api/projects/{id}', [ProjectApiController::class, 'update']);
$router->delete('/api/projects/{id}', [ProjectApiController::class, 'delete']);
$router->post('/api/projects/{id}/members', [ProjectApiController::class, 'addMember']);
$router->delete('/api/projects/{id}/members/{employeeId}', [ProjectApiController::class, 'removeMember']);

// Employees API
$router->get('/api/employees', [EmployeeApiController::class, 'index']);
$router->get('/api/employees/{id}', [EmployeeApiController::class, 'show']);
$router->post('/api/employees', [EmployeeApiController::class, 'store']);
$router->put('/api/employees/{id}', [EmployeeApiController::class, 'update']);
$router->delete('/api/employees/{id}', [EmployeeApiController::class, 'delete']);

// Leaves API
$router->get('/api/leaves', [LeaveApiController::class, 'index']);
$router->get('/api/leaves/{id}', [LeaveApiController::class, 'show']);
$router->post('/api/leaves', [LeaveApiController::class, 'store']);
$router->get('/api/leave-types', [LeaveApiController::class, 'leaveTypes']);
$router->get('/api/leave-balance', [LeaveApiController::class, 'balance']);

// Expenses API
$router->get('/api/expenses', [ExpenseApiController::class, 'index']);
$router->get('/api/expenses/{id}', [ExpenseApiController::class, 'show']);
$router->post('/api/expenses', [ExpenseApiController::class, 'store']);
$router->delete('/api/expenses/{id}', [ExpenseApiController::class, 'delete']);

// Timesheets API
$router->get('/api/timesheets', [TimesheetApiController::class, 'index']);
$router->get('/api/timesheets/{id}', [TimesheetApiController::class, 'show']);
$router->post('/api/timesheets', [TimesheetApiController::class, 'store']);
$router->delete('/api/timesheets/{id}', [TimesheetApiController::class, 'delete']);

// Dispatch
$router->dispatch();
