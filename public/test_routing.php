<?php
// Quick routing diagnostic
header('Content-Type: application/json');

echo json_encode([
    'status' => 'success',
    'message' => 'Direct PHP access works',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'not set',
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? 'not set',
    'php_self' => $_SERVER['PHP_SELF'] ?? 'not set',
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'not set',
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'not set',
    'mod_rewrite' => function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? 'enabled' : 'unknown',
    'htaccess_test' => file_exists(__DIR__ . '/.htaccess') ? 'exists' : 'missing'
]);
