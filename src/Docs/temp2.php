<?php
    // public static function get(string $path, array $handler): void
    // {
    //     self::getInstance()->addRoute('GET', $path, $handler);
    // }

    // public static function post(string $path, array $handler): void
    // {
    //     self::getInstance()->addRoute('POST', $path, $handler);
    // }

    // public static function put(string $path, array $handler): void
    // {
    //     self::getInstance()->addRoute('PUT', $path, $handler);
    // }

    // public static function delete(string $path, array $handler): void
    // {
    //     self::getInstance()->addRoute('DELETE', $path, $handler);
    // }

    // private function addRoute(string $method, string $path, array $handler): void
    // {
    //     $this->routes[$method][$path] = $handler;
    // }

    //     public function handleRequest(): void
//     {
//     $method = $_SERVER['REQUEST_METHOD'];
//     $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//     if (!isset($this->routes[$method])) {
//         $this->sendNotFound();
//         return;
//     }

//     foreach ($this->routes[$method] as $route => $handler) {
//         $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*');
//         if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
//             array_shift($matches);

//             // Verifica se o handler é uma função anônima
//             if (is_callable($handler)) {
//                 call_user_func_array($handler, $matches);
//                 return;
//             }

//             // Caso contrário, assume que é um controlador
//             [$controller, $action] = $handler;
//             $inputData = ($method === 'POST' || $method === 'PUT')
//                 ? json_decode(file_get_contents('php://input'), true)
//                 : [];

//             (new $controller())->$action($inputData, ...$matches);
//             return;
//         }
//     }

//     $this->sendNotFound();
// }
    // public function handleRequest(): void
    // {
    //     $method = $_SERVER['REQUEST_METHOD'];
    //     $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    //     if (!isset($this->routes[$method])) {
    //         $this->sendNotFound();
    //         return;
    //     }

    //     foreach ($this->routes[$method] as $route => [$controller, $action]) {
    //         // $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*');
    //         $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*');
    //         if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
    //             array_shift($matches);
    //             error_log("Parâmetros capturados: " . json_encode($matches)); // Log para depuração

    //             $inputData = ($method === 'POST' || $method === 'PUT')
    //                 ? json_decode(file_get_contents('php://input'), true)
    //                 : [];

    //             (new $controller())->$action($inputData, ...$matches);
    //             return;
    //         }
    //     }

    //     $this->sendNotFound();
    // }