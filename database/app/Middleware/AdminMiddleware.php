<?php

class AdminMiddleware
{
    public function handle()
    {
        Session::start();
        
        if (!Session::has('user')) {
            $baseUrl = dirname($_SERVER['SCRIPT_NAME']);
            $baseUrl = $baseUrl === '/' ? '' : $baseUrl;
            header("Location: $baseUrl/login");
            exit;
        }

        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            echo "403 - Forbidden: Admin access required";
            exit;
        }
        
        return true;
    }
}
