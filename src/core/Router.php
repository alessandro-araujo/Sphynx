<?php

namespace Core;

class Router
{
    private static ?Router $instance = null;
    private array $routes = [];

    private function __construct()
    {
        register_shutdown_function([$this, 'handleRequest']);
    }

    public static function getInstance(): Router
    {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public static function get(string $path, array|callable $handler, ?string $middleware = null): void
    {
        self::getInstance()->addRoute('GET', $path, $handler, $middleware);
    }

    public static function post(string $path, array|callable $handler, ?string $middleware = null): void
    {
        self::getInstance()->addRoute('POST', $path, $handler, $middleware);
    }

    public static function put(string $path, array|callable $handler, ?string $middleware = null): void
    {
        self::getInstance()->addRoute('PUT', $path, $handler, $middleware);
    }

    public static function delete(string $path, array|callable $handler, ?string $middleware = null): void
    {
        self::getInstance()->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute(string $method, string $path, array|callable $handler, ?string $middleware = null): void
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method])) {
            $this->sendNotFound();
            return;
        }

        foreach ($this->routes[$method] as $route => $routeData) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*');
            if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
                array_shift($matches);

                $handler = $routeData['handler'];
                $middleware = $routeData['middleware'] ?? null;

                $inputData = ($method === 'POST' || $method === 'PUT')
                    ? json_decode(file_get_contents('php://input'), true)
                    : [];

                $callable = function () use ($handler, $inputData, $matches) {
                    if (is_callable($handler)) {
                        call_user_func_array($handler, [$inputData, ...$matches]);
                    } else {
                        [$controller, $action] = $handler;
                        call_user_func_array([new $controller(), $action], [$inputData, ...$matches]);
                    }
                };

                if ($middleware) {
                    $middlewareInstance = new $middleware();
                    $middlewareInstance->handle($callable);
                } else {
                    $callable();
                }

                return;
            }
        }

        $this->sendNotFound();
    }

    private function sendNotFound(): void
    {
        http_response_code(404);
        echo json_encode(["error" => "Rota não encontrada"]);
    }
}
