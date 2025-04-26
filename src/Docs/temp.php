
<?php
//// FUNCIONA 2
// require __DIR__ . '/../../vendor/autoload.php';

// use Core\Router;
// use App\Controllers\FilmsController;
// use App\Controllers\LogsController;

// // Registre as rotas diretamente
// Router::get('/films', [FilmsController::class, 'index']);
// Router::get('/films/{id}', [FilmsController::class, 'show']);
// Router::post('/logs', [LogsController::class, 'store']);
// Router::delete('/films/{id}', [FilmsController::class, 'destroy']);

// // Processa a requisição automaticamente
// Router::getInstance()->handleRequest();





// namespace Router;
// require __DIR__ . '../../vendor/autoload.php';

// Exemplo de uso
// use App\Controllers\LogsController;

//// FUNCIONA
// use App\Controllers\FilmsController;
// use Core\Router;

// $router = new Router();
// $router->get('/films', [FilmsController::class, 'index']);
// $router->get('/films/{id}', [FilmsController::class, 'show']);

// $router->handleRequest();
// $router->get('/films', [FilmsController::class, 'index']);
// $router->get('/films/{id}', [FilmsController::class, 'show']);
// $router->post('/logs', [LogsController::class, 'store']);
// $router->delete('/films/{id}', [FilmsController::class, 'destroy']);





// namespace Core\Router;

//

// namespace Core;

// class Router 
// {
//     private array $routes = [];

//     public function addRoute(string $method, string $path, array $handler): void
//     {
//         $this->routes[$method][$path] = $handler;
//     }

//     public function get(string $path, array $handler): void
//     {
//         $this->addRoute('GET', $path, $handler);
//     }

//     public function post(string $path, array $handler): void
//     {
//         $this->addRoute('POST', $path, $handler);
//     }

//     public function put(string $path, array $handler): void
//     {
//         $this->addRoute('PUT', $path, $handler);
//     }

//     public function delete(string $path, array $handler): void
//     {
//         $this->addRoute('DELETE', $path, $handler);
//     }

//     public function handleRequest(): void
//     {
//         $method = $_SERVER['REQUEST_METHOD'];
//         $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//         if (!isset($this->routes[$method])) {
//             $this->sendNotFound();
//             return;
//         }

//         foreach ($this->routes[$method] as $route => [$controller, $action]) {
//             // Substituir parâmetros dinâmicos no padrão
//             $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*');
//             if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
//                 array_shift($matches);

//                 // Capturar dados JSON para métodos POST e PUT
//                 $inputData = ($method === 'POST' || $method === 'PUT') 
//                     ? json_decode(file_get_contents('php://input'), true) 
//                     : [];

//                 (new $controller())->$action($inputData, ...$matches);
//                 return;
//             }
//         }

//         $this->sendNotFound();
//     }

//     private function sendNotFound(): void
//     {
//         http_response_code(404);
//         echo json_encode(["error" => "Rota não encontrada"]);
//     }

//     public function getRoutes(): array
//     {
//         return $this->routes;
//     }
// }

// // Exemplo de uso
// use App\Controllers\FilmsController;
// use App\Controllers\LogsController;

// $router = new Router();
// $router->get('/films', [FilmsController::class, 'index']);
// $router->get('/films/{id}', [FilmsController::class, 'show']);
// $router->post('/logs', [LogsController::class, 'store']);
// $router->delete('/films/{id}', [FilmsController::class, 'destroy']);

// // Lidar com a requisição
// $router->handleRequest();
//     // use App\Router\Router; // Ensure the Router class exists in the namespace App\Router

    // $app = new Router();
    // $app->addRoute('GET', '/example', function() {
    //     echo "Hello, World!";
    // });



    /// FUNCIONA 2
// namespace Core;

// class Router
// {
//     private static ?Router $instance = null;
//     private array $routes = [];

//     private function __construct()
//     {
//         // O construtor é privado para evitar múltiplas instâncias
//     }

//     public static function getInstance(): Router
//     {
//         if (self::$instance === null) {
//             self::$instance = new Router();
//         }
//         return self::$instance;
//     }

//     public static function get(string $path, array $handler): void
//     {
//         self::getInstance()->addRoute('GET', $path, $handler);
//     }

//     public static function post(string $path, array $handler): void
//     {
//         self::getInstance()->addRoute('POST', $path, $handler);
//     }

//     public static function put(string $path, array $handler): void
//     {
//         self::getInstance()->addRoute('PUT', $path, $handler);
//     }

//     public static function delete(string $path, array $handler): void
//     {
//         self::getInstance()->addRoute('DELETE', $path, $handler);
//     }

//     private function addRoute(string $method, string $path, array $handler): void
//     {
//         $this->routes[$method][$path] = $handler;
//     }

//     public function handleRequest(): void
//     {
//         $method = $_SERVER['REQUEST_METHOD'];
//         $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//         if (!isset($this->routes[$method])) {
//             $this->sendNotFound();
//             return;
//         }

//         foreach ($this->routes[$method] as $route => [$controller, $action]) {
//             $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*');
//             if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
//                 array_shift($matches);

//                 $inputData = ($method === 'POST' || $method === 'PUT')
//                     ? json_decode(file_get_contents('php://input'), true)
//                     : [];

//                 (new $controller())->$action($inputData, ...$matches);
//                 return;
//             }
//         }

//         $this->sendNotFound();
//     }

//     private function sendNotFound(): void
//     {
//         http_response_code(404);
//         echo json_encode(["error" => "Rota não encontrada"]);
//     }
// }





/// FUNCIONA
// namespace Core;

// class Router 
// {
//     private array $routes = [];

//     public function __construct()
//     {
//         // $this->handleRequest();
//     }

//     public function addRoute(string $method, string $path, array $handler): void
//     {
//         $this->routes[$method][$path] = $handler;
//     }

//     public function get(string $path, array $handler): void
//     {
//         $this->addRoute('GET', $path, $handler);
//     }

//     public function post(string $path, array $handler): void
//     {
//         $this->addRoute('POST', $path, $handler);
//     }

//     public function put(string $path, array $handler): void
//     {
//         $this->addRoute('PUT', $path, $handler);
//     }

//     public function delete(string $path, array $handler): void
//     {
//         $this->addRoute('DELETE', $path, $handler);
//     }

//     public function handleRequest(): void
//     {
//         $method = $_SERVER['REQUEST_METHOD'];
//         $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//         error_log("Método: $method, URI: $uri"); // Log para depuração

//         if (!isset($this->routes[$method])) {
//             $this->sendNotFound();
//             return;
//         }

//         foreach ($this->routes[$method] as $route => [$controller, $action]) {
//             $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*');
//             if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
//                 array_shift($matches);

//                 $inputData = ($method === 'POST' || $method === 'PUT') 
//                     ? json_decode(file_get_contents('php://input'), true) 
//                     : [];

//                 (new $controller())->$action($inputData, ...$matches);
//                 return;
//             }
//         }

//         $this->sendNotFound();
//     }

//     private function sendNotFound(): void
//     {
//         http_response_code(404);
//         echo json_encode(["error" => "Rota não encontrada"]);
//     }

//     public function getRoutes(): array
//     {
//         return $this->routes;
//     }
// }










// namespace Core;
// use App\Controllers\FilmsController;
// use App\Controllers\LogsController;
// use App\Controllers\CommentController;

// class Router {
//     private array $routes = [];

//     public function __construct() {
//         $this->routes = [
//             'GET' => [
//                 '/films' => [FilmsController::class, 'index'],
//                 '/films/{id}' => [FilmsController::class, 'show'],
//                 '/logs/{pag}' => [LogsController::class, 'show'],
//                 '/comment/{id}' => [CommentController::class, 'show'],
//             ],
//             'POST' => [
//                 '/favorites' => [FilmsController::class, 'favorites_store'],
//                 '/logs' => [LogsController::class, 'store'],
//                 '/comment' => [CommentController::class, 'store'],
//             ],
//             'PUT' => [
                
//             ],
//             'DELETE' => [
//                '/favorites/{id}' => [FilmsController::class, 'favorites_destroy'],
//             ]
//         ];
//     }
    
//     public function handleRequest() {
//         $method = $_SERVER['REQUEST_METHOD'];
//         $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
//         foreach ($this->routes[$method] as $route => [$controller, $action]) {
//             // Adicione a barra final ao padrão
//             $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', rtrim($route, '/') . '/*'); 
//             if (preg_match("#^{$pattern}$#", rtrim($uri, '/'), $matches)) {
//                 array_shift($matches);

//                 // Para POST, capturar o JSON enviado
//                 if ($method === 'POST' || $method === 'PUT') {
//                     $inputData = json_decode(file_get_contents('php://input'), true);
//                     (new $controller())->$action($inputData, ...$matches);
//                 }else {
//                     (new $controller())->$action(...$matches);
//                 }
//                 return;
//             }
//         }
        
//         http_response_code(404);
//         echo json_encode(["error" => "Rota não encontrada"]);
//     }
// }
