<?php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Request.php';
require_once __DIR__ . '/../core/Response.php';
require_once __DIR__ . '/../core/Database.php';

// Start session
session_start();

// Create router instance
$router = new Router();

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Get request URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Dispatch route
$router->dispatch($uri, $method);