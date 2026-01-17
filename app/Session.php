<?php

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function flash($key, $value = null)
    {
        if ($value === null) {
            $flash = self::get("flash_$key");
            self::remove("flash_$key");
            return $flash;
        }
        
        self::set("flash_$key", $value);
    }

    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    public static function csrfToken()
    {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return self::get('csrf_token');
    }

    public static function validateCsrfToken($token)
    {
        return hash_equals(self::csrfToken(), $token);
    }
}
