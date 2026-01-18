<?php

class ApiController extends Controller
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
    }

    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function errorResponse($message, $statusCode = 400)
    {
        $this->jsonResponse(['error' => $message], $statusCode);
    }

    protected function successResponse($data, $message = null)
    {
        $response = ['success' => true];
        if ($message) {
            $response['message'] = $message;
        }
        if ($data) {
            $response['data'] = $data;
        }
        $this->jsonResponse($response);
    }

    protected function validateApiRequest()
    {
        $token = $this->getBearerToken();
        
        if (!$token) {
            $this->errorResponse('Authorization token required', 401);
        }

        $user = $this->validateToken($token);
        
        if (!$user) {
            $this->errorResponse('Invalid or expired token', 401);
        }

        // Store user in session-like manner for controller access
        $_SESSION['api_user'] = $user;
        
        return $user;
    }

    protected function getBearerToken()
    {
        $headers = apache_request_headers();
        
        if (isset($headers['Authorization'])) {
            $matches = [];
            if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    protected function validateToken($token)
    {
        $tokenData = $this->db->fetchOne(
            "SELECT u.*, e.id as employee_id, e.full_name, e.designation, e.department, e.manager_id
             FROM api_tokens t
             INNER JOIN users u ON t.user_id = u.id
             LEFT JOIN employees e ON e.user_id = u.id
             WHERE t.token = ? AND t.expires_at > NOW() AND u.is_active = 1",
            [$token]
        );

        return $tokenData ?: null;
    }

    protected function getJsonInput()
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?: [];
    }

    protected function getCurrentEmployee()
    {
        if (isset($_SESSION['api_user']) && $_SESSION['api_user']['employee_id']) {
            return [
                'id' => $_SESSION['api_user']['employee_id'],
                'full_name' => $_SESSION['api_user']['full_name'],
                'designation' => $_SESSION['api_user']['designation'],
                'department' => $_SESSION['api_user']['department'],
                'manager_id' => $_SESSION['api_user']['manager_id']
            ];
        }
        
        $this->errorResponse('Employee profile not found', 404);
    }

    protected function getCurrentUser()
    {
        if (isset($_SESSION['api_user'])) {
            return [
                'id' => $_SESSION['api_user']['id'],
                'email' => $_SESSION['api_user']['email'],
                'role' => $_SESSION['api_user']['role']
            ];
        }
        
        $this->errorResponse('User not authenticated', 401);
    }

    protected function isAdmin()
    {
        return isset($_SESSION['api_user']) && $_SESSION['api_user']['role'] === 'admin';
    }
}

