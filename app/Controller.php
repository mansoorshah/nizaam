<?php

class Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function view($viewName, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/Views/' . str_replace('.', '/', $viewName) . '.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("View not found: $viewName");
        }
    }

    protected function redirect($path)
    {
        $baseUrl = $this->getBaseUrl();
        header("Location: $baseUrl$path");
        exit;
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function getBaseUrl()
    {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        return $scriptName === '/' ? '' : $scriptName;
    }

    protected function getCurrentUser()
    {
        return $_SESSION['user'] ?? null;
    }

    protected function getCurrentEmployee()
    {
        return $_SESSION['employee'] ?? null;
    }

    protected function isAdmin()
    {
        $user = $this->getCurrentUser();
        return $user && $user['role'] === 'admin';
    }
}
