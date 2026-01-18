<?php
// Debug script for project create
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/app/Database.php';
require_once __DIR__ . '/app/Model.php';
require_once __DIR__ . '/app/Models/Employee.php';
require_once __DIR__ . '/app/Session.php';
require_once __DIR__ . '/config/database.php';

Session::start();

$employeeModel = new Employee();

try {
    echo "Testing getAllWithUsers method...\n\n";
    $employees = $employeeModel->getAllWithUsers(['employment_status' => 'active']);
    
    echo "Success! Found " . count($employees) . " employees:\n\n";
    
    foreach ($employees as $emp) {
        echo "- {$emp['full_name']} ({$emp['email']})\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString();
}
