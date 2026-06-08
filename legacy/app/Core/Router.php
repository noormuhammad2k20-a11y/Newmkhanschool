<?php
namespace App\Core;

class Router {
    private array $routes = [];

    /**
     * Add a route mapping.
     * @param string $method GET, POST, PUT, DELETE, etc.
     * @param string $route e.g., /api/students/{id}
     * @param string|callable $handler e.g., 'StudentController@show'
     */
    public function add(string $method, string $route, $handler): void {
        // Convert route wildcard format like {id} to named group regex (?P<id>[^/]+)
        $routeRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route);
        $routeRegex = '#^' . $routeRegex . '$#';

        $this->routes[] = [
            'method' => strtoupper($method),
            'regex' => $routeRegex,
            'handler' => $handler
        ];
    }

    /**
     * Dispatch the current request to a matched route.
     */
    public function dispatch(string $method, string $uri): ?array {
        $uri = parse_url($uri, PHP_URL_PATH);
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['regex'], $uri, $matches)) {
                // Keep only string keys (named groups)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [
                    'handler' => $route['handler'],
                    'params' => $params
                ];
            }
        }
        return null;
    }
}
