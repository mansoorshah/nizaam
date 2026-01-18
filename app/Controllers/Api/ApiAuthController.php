<?php

class ApiAuthController extends Controller
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
    }

    // POST /api/auth/login
    public function login()
    {
        header('Content-Type: application/json');

        if (!Request::isPost()) {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';
        $password = $input['password'] ?? '';

        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password are required']);
            exit;
        }

        // Find user
        $user = $this->db->fetchOne(
            "SELECT * FROM users WHERE email = ? AND is_active = 1",
            [$email]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            exit;
        }

        // Generate token
        $token = $this->generateToken($user['id']);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Store token in database
        $this->db->insert('api_tokens', [
            'user_id' => $user['id'],
            'token' => $token,
            'expires_at' => $expiresAt
        ]);

        // Get employee details
        $employee = $this->db->fetchOne(
            "SELECT e.*, u.email, u.role 
             FROM employees e 
             INNER JOIN users u ON e.user_id = u.id 
             WHERE u.id = ?",
            [$user['id']]
        );

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 86400, // 24 hours in seconds
            'expires_at' => $expiresAt,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'employee_id' => $employee['id'] ?? null,
                'full_name' => $employee['full_name'] ?? null
            ]
        ]);
        exit;
    }

    // POST /api/auth/logout
    public function logout()
    {
        header('Content-Type: application/json');

        $token = $this->getBearerToken();
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'No token provided']);
            exit;
        }

        // Delete token
        $this->db->query("DELETE FROM api_tokens WHERE token = ?", [$token]);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
        exit;
    }

    // POST /api/auth/refresh
    public function refresh()
    {
        header('Content-Type: application/json');

        $token = $this->getBearerToken();
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'No token provided']);
            exit;
        }

        // Verify old token
        $tokenData = $this->db->fetchOne(
            "SELECT * FROM api_tokens WHERE token = ? AND expires_at > NOW()",
            [$token]
        );

        if (!$tokenData) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }

        // Delete old token
        $this->db->query("DELETE FROM api_tokens WHERE token = ?", [$token]);

        // Generate new token
        $newToken = $this->generateToken($tokenData['user_id']);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // Store new token
        $this->db->insert('api_tokens', [
            'user_id' => $tokenData['user_id'],
            'token' => $newToken,
            'expires_at' => $expiresAt
        ]);

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'token' => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => 86400,
            'expires_at' => $expiresAt
        ]);
        exit;
    }

    // GET /api/auth/me
    public function me()
    {
        header('Content-Type: application/json');

        $token = $this->getBearerToken();
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'No token provided']);
            exit;
        }

        $user = $this->validateToken($token);
        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }

        // Get employee details
        $employee = $this->db->fetchOne(
            "SELECT e.*, u.email, u.role 
             FROM employees e 
             INNER JOIN users u ON e.user_id = u.id 
             WHERE u.id = ?",
            [$user['id']]
        );

        http_response_code(200);
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'employee_id' => $employee['id'] ?? null,
                'employee_code' => $employee['employee_id'] ?? null,
                'full_name' => $employee['full_name'] ?? null,
                'designation' => $employee['designation'] ?? null,
                'department' => $employee['department'] ?? null
            ]
        ]);
        exit;
    }

    private function generateToken($userId)
    {
        return bin2hex(random_bytes(32)) . '_' . $userId . '_' . time();
    }

    private function getBearerToken()
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

    private function validateToken($token)
    {
        $tokenData = $this->db->fetchOne(
            "SELECT u.* FROM api_tokens t
             INNER JOIN users u ON t.user_id = u.id
             WHERE t.token = ? AND t.expires_at > NOW() AND u.is_active = 1",
            [$token]
        );

        return $tokenData ?: null;
    }
}
