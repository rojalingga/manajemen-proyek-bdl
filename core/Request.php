<?php

class Request {
    public static function all() {
        return $_REQUEST;
    }
    
    public static function get($key, $default = null) {
        return $_REQUEST[$key] ?? $default;
    }
    
    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public static function uri() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
