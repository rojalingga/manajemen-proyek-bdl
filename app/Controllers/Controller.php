<?php

class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . "/../../views/{$view}.php";
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            throw new Exception("View not found: {$view}");
        }
    }
}