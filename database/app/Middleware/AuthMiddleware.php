<?php

class AuthMiddleware
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
        
        return true;
    }
}
