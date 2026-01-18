<?php

class GuestMiddleware
{
    public function handle()
    {
        Session::start();
        
        if (Session::has('user')) {
            $baseUrl = dirname($_SERVER['SCRIPT_NAME']);
            $baseUrl = $baseUrl === '/' ? '' : $baseUrl;
            header("Location: $baseUrl/dashboard");
            exit;
        }
        
        return true;
    }
}
