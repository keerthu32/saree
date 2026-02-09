<?php

declare(strict_types=1);

namespace App;

final class Router
{
    /** @var array<string, array<int, array{pattern:string,handler:callable}>> */
    private array $routes = [];

    public function add(string $method, string $path, callable $handler): void
    {
        $pattern = '#^' . preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path) . '$#';
        $this->routes[strtoupper($method)][] = [
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $route) {
            if (preg_match($route['pattern'], $path, $matches) === 1) {
                $params = array_filter($matches, static fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                $route['handler']($params);
                return;
            }
        }

        Response::error('Route not found', 404);
    }
}
