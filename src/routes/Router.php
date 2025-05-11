<?php

use Core\Router;
use App\Controllers\Login;
// use App\Controllers\ExampleController;
use App\Middleware\AuthMiddleware;
use Database\InlineSQL;

$args = ['connection' => new InlineSQL()];
Router::post('/login', [Login::class, 'login'], null, $args);
// Router::get('/exampleController', [ExampleController::class, 'index'], AuthMiddleware::class);

// Router::get('/user', [UserController::class, 'getUser'], AuthMiddleware::class);
// Router::get('/exampleController/{id}', [ExampleController::class, 'show']);
// Router::post('/exampleController', [ExampleController::class, 'store']);
// Router::delete('/exampleController/{id}', [ExampleController::class, 'destroy']);
// Router::put('/exampleController/{id}', [ExampleController::class, 'update']);

Router::get('/example', function() {
    echo json_encode(["message" => "Hello, World!"]);
});

Router::get('/example/{name}', function(string $name)  {
    echo json_encode(["message" => "Hello, $name"]);
});

Router::post('/example', function(array $data) {
    echo json_encode(["message" => "Exemplo criado com sucesso", "data" => $data]);
});
