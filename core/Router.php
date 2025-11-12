<?php

class Router {
    private $routes = [];
    
    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }
    
    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }
    
    public function put($uri, $action) {
        $this->routes['PUT'][$uri] = $action;
    }
    
    public function delete($uri, $action) {
        $this->routes['DELETE'][$uri] = $action;
    }
    
    public function dispatch($uri, $method) {
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        if (isset($this->routes[$method][$uri])) {
            return $this->executeAction($this->routes[$method][$uri]);
        }
        
        foreach ($this->routes[$method] ?? [] as $route => $action) {
            $pattern = $this->convertToRegex($route);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                return $this->executeAction($action, $matches);
            }
        }
        
        http_response_code(404);
        echo "404 - Page Not Found";
    }
    
    private function convertToRegex($route) {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route);
        return '#^' . $pattern . '$#';
    }
    
    private function executeAction($action, $params = []) {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }
        
        if (is_string($action)) {
            list($controller, $method) = explode('@', $action);
            $controllerFile = __DIR__ . "/../app/Controllers/{$controller}.php";
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                return call_user_func_array([$controllerInstance, $method], $params);
            }
        }
        
        throw new Exception("Invalid action");
    }
}
