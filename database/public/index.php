<?php

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload core files
require_once __DIR__ . '/../app/Database.php';
require_once __DIR__ . '/../app/Router.php';
require_once __DIR__ . '/../app/Controller.php';
require_once __DIR__ . '/../app/Model.php';
require_once __DIR__ . '/../app/Session.php';
require_once __DIR__ . '/../app/Request.php';

// Autoload middleware
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../app/Middleware/AdminMiddleware.php';
require_once __DIR__ . '/../app/Middleware/GuestMiddleware.php';

// Autoload models
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Employee.php';
require_once __DIR__ . '/../app/Models/WorkItem.php';
require_once __DIR__ . '/../app/Models/Workflow.php';
require_once __DIR__ . '/../app/Models/Project.php';
require_once __DIR__ . '/../app/Models/Notification.php';
require_once __DIR__ . '/../app/Models/AuditLog.php';

// Autoload services
require_once __DIR__ . '/../app/Services/WorkflowService.php';
require_once __DIR__ . '/../app/Services/LeaveService.php';

// Autoload controllers
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/EmployeeController.php';
require_once __DIR__ . '/../app/Controllers/WorkItemController.php';
require_once __DIR__ . '/../app/Controllers/ProjectController.php';
require_once __DIR__ . '/../app/Controllers/LeaveController.php';
require_once __DIR__ . '/../app/Controllers/NotificationController.php';
require_once __DIR__ . '/../app/Controllers/ReportController.php';
require_once __DIR__ . '/../app/Controllers/AuditController.php';
require_once __DIR__ . '/../app/Controllers/ExpenseController.php';
require_once __DIR__ . '/../app/Controllers/TimesheetController.php';

// Set timezone
date_default_timezone_set('UTC');

// Load routes
require_once __DIR__ . '/../app/routes.php';
