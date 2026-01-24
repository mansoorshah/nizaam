<?php

class Request
{
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function isPost()
    {
        return self::method() === 'POST';
    }

    public static function isGet()
    {
        return self::method() === 'GET';
    }

    public static function input($key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public static function all()
    {
        return array_merge($_GET, $_POST);
    }

    public static function has($key)
    {
        return isset($_POST[$key]) || isset($_GET[$key]);
    }

    public static function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    public static function ip()
    {
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    public static function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    public static function validate($rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            $value = self::input($field);

            foreach ($rulesArray as $rule) {
                if ($rule === 'required' && empty($value)) {
                    $errors[$field] = ucfirst($field) . ' is required';
                    break;
                }

                if (strpos($rule, 'min:') === 0 && strlen($value) < substr($rule, 4)) {
                    $errors[$field] = ucfirst($field) . ' must be at least ' . substr($rule, 4) . ' characters';
                    break;
                }

                if (strpos($rule, 'max:') === 0 && strlen($value) > substr($rule, 4)) {
                    $errors[$field] = ucfirst($field) . ' must not exceed ' . substr($rule, 4) . ' characters';
                    break;
                }

                if ($rule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = ucfirst($field) . ' must be a valid email';
                    break;
                }

                if ($rule === 'numeric' && !is_numeric($value)) {
                    $errors[$field] = ucfirst($field) . ' must be numeric';
                    break;
                }
            }
        }

        return empty($errors) ? true : $errors;
    }

    /**
     * Validate CSRF token for POST requests
     * CRITICAL SECURITY FIX: Enforce CSRF protection
     */
    public static function validateCsrf()
    {
        if (self::isPost()) {
            $token = self::input('csrf_token');
            if (!$token || !Session::validateCsrfToken($token)) {
                http_response_code(403);
                die('CSRF token validation failed');
            }
        }
        return true;
    }
}
