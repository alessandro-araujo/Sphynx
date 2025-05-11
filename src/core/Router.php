<?php

namespace Core;

class Router {
    private static ?Router $instance = null;
     /**
      * @var array<string, array<string, array{handler: callable|array{0: class-string, 1: string}, middleware?: string|null, args?: array<string|int, mixed>}>>
      */
    private array $routes = [];

    private function __construct() {
        register_shutdown_function([$this, 'handleRequest']);
    }

    public static function getInstance(): Router {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    /**
     * @param string $path
     * @param callable|array{0: class-string, 1: string} $handler
     * @param string|null $middleware
     * @param array<string|int, mixed> $args
     * @return void
     */
    public static function get(string $path, array|callable $handler, ?string $middleware = null, array $args = []): void {
        self::getInstance()->addRoute('GET', $path, $handler, $middleware, $args);
    }

     /**
      * @param string $path
      * @param callable|array{0: class-string, 1: string} $handler
      * @param string|null $middleware
      * @param array<string|int, mixed> $args
      * @return void
      */
    public static function post(string $path, array|callable $handler, ?string $middleware = null, array $args = []): void {
        self::getInstance()->addRoute('POST', $path, $handler, $middleware, $args);
    }

    /**
     * @param string $path
     * @param callable|array{0: class-string, 1: string} $handler
     * @param string|null $middleware
     * @param array<string|int, mixed> $args
     * @return void
     */
    public static function put(string $path, array|callable $handler, ?string $middleware = null, array $args = []): void {
        self::getInstance()->addRoute('PUT', $path, $handler, $middleware, $args);
    }

    /**
     * @param string $path
     * @param callable|array{0: class-string, 1: string} $handler
     * @param string|null $middleware
     * @param array<string|int, mixed> $args
     * @return void
     */
    public static function delete(string $path, array|callable $handler, ?string $middleware = null, array $args = []): void {
        self::getInstance()->addRoute('DELETE', $path, $handler, $middleware, $args);
    }

     /**
      * @param string $method
      * @param string $path
      * @param callable|array{0: class-string, 1: string} $handler
      * @param string|null $middleware
      * @param array<string|int, mixed> $args
      * @return void
      */
    private function addRoute(string $method, string $path, array|callable $handler, ?string $middleware = null, array $args = []): void {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware,
            'args' => $args
        ];
    }

    public function handleRequest(): void {
         /** @var string $method */      $method = $_SERVER['REQUEST_METHOD'];
         /** @var string $request_uri */ $request_uri = $_SERVER['REQUEST_URI'];
         /** @var string $uri */         $uri = parse_url($request_uri, PHP_URL_PATH);

        if (!isset($this->routes[$method])) {
            $this->sendNotFound();
            return;
        }

        foreach ($this->routes[$method] as $route => $route_data) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)}/', '([^/]+)', rtrim($route, '/') . '/*');
            if (preg_match("#^$pattern$#", rtrim($uri, '/'), $matches)) {
                array_shift($matches);

                $handler = $route_data['handler'];
                $middleware = $route_data['middleware'] ?? null;
                $args = $route_data['args'] ?? [];
                $inputData = ($method === 'POST' || $method === 'PUT')
                    ? json_decode(file_get_contents('php://input') ?: '', true)
                    : [];

                $callable = function () use ($handler, $inputData, $matches, $args) {
                    if (is_callable($handler)) {
                        call_user_func_array($handler, [$inputData, ...$matches, $args]);
                    } else {
                        [$controller, $action] = $handler;
                        /** @phpstan-ignore-next-line */
                        call_user_func_array([new $controller(), $action], [$inputData, ...$matches, $args]);
                    }
                };

                if ($middleware) {
                    $middlewareInstance = new $middleware();
                    /** @phpstan-ignore-next-line */
                    $middlewareInstance->handle($callable);
                } else {
                    $callable();
                }
                return;
            }
        }

        $this->sendNotFound();
    }

    private function sendNotFound(): void {
        http_response_code(404);
        echo json_encode(["error" => "Rota não encontrada"]);
    }
}