<?php
// Autoloader for App\ namespace (PSR-4 compliant mapping to /app)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

// Initialize session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();

// Define API Routes
$router->add('GET', '/api/students', 'StudentController@index');
$router->add('POST', '/api/students', 'StudentController@store');
$router->add('GET', '/api/teachers', 'TeacherController@index');
$router->add('POST', '/api/teachers', 'TeacherController@store');

// Get request parameters and strip subdirectory prefix if running under one (e.g. /school)
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$uri = $_SERVER['REQUEST_URI'];

if ($basePath !== '/' && $basePath !== '\\') {
    $basePath = rtrim($basePath, '/\\');
    if (str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath));
    }
}

// Clean query string for dispatch lookup
$routeUri = parse_url($uri, PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

// Dispatch route
$route = $router->dispatch($method, $routeUri);

if ($route) {
    $handler = $route['handler'];
    $params = $route['params'];

    list($controllerName, $action) = explode('@', $handler);
    $controllerClass = "App\\Controllers\\" . $controllerName;

    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        } else {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => "Action '{$action}' not found in controller '{$controllerName}'."]);
        }
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => "Controller class '{$controllerClass}' not found."]);
    }
} else {
    // Graceful Fallback:
    // If the path requested matches an existing legacy PHP script in the root directory, execute it.
    // This maintains immediate compatibility during migration.
    $path = parse_url($uri, PHP_URL_PATH);
    $requestedFile = __DIR__ . '/' . ltrim($path, '/');

    if (file_exists($requestedFile) && is_file($requestedFile) && str_ends_with($requestedFile, '.php')) {
        include $requestedFile;
        exit;
    }

    // Default redirect to Admin Dashboard legacy page if landing on root
    if ($path === '/' || $path === '/index.php') {
        header('Location: /Admin Dashboard.php');
        exit;
    }

    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => "Route not found: {$uri}"]);
}
