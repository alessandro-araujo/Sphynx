<?php
namespace Core;
use App\Controllers\FilmsController;
use App\Controllers\LogsController;
use App\Controllers\CommentController;

class Router {
    private array $routes = [];

    public function __construct() {
        $this->routes = [
            'GET' => [
                '/films' => [FilmsController::class, 'index'],
                '/films/{id}' => [FilmsController::class, 'show'],
                '/logs/{pag}' => [LogsController::class, 'show'],
                '/comment/{id}' => [CommentController::class, 'show'],
            ],
            'POST' => [
                '/favorites' => [FilmsController::class, 'favorites_store'],
                '/logs' => [LogsController::class, 'store'],
                '/comment' => [CommentController::class, 'store'],
            ],
            'PUT' => [
                
            ],
            'DELETE' => [
               '/favorites/{id}' => [FilmsController::class, 'favorites_destroy'],
            ]
        ];
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
        foreach ($this->routes[$method] as $route => [$controller, $action]) {
            // Adicione a barra final ao padrão
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*'); 
            if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
                array_shift($matches);

                // Para POST, capturar o JSON enviado
                if ($method === 'POST' || $method === 'PUT') {
                    $inputData = json_decode(file_get_contents('php://input'), true);
                    (new $controller())->$action($inputData, ...$matches);
                }else {
                    (new $controller())->$action(...$matches);
                }
                return;
            }
        }
        
        http_response_code(404);
        echo json_encode(["error" => "Rota não encontrada"]);
    }
}
