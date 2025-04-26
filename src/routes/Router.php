<?php

require __DIR__ . '/../../vendor/autoload.php';

use Core\Router;
use App\Controllers\ExampleController;


Router::get('/exampleController', [ExampleController::class, 'index']);

Router::get('/exampleController/{id}', [ExampleController::class, 'show']);
Router::post('/exampleController', [ExampleController::class, 'store']);
Router::delete('/exampleController/{id}', [ExampleController::class, 'destroy']);
Router::put('/exampleController/{id}', [ExampleController::class, 'update']);

Router::get('/example', function() {
    echo json_encode(["message" => "Hello, World!"]);
});

Router::get('/example/{name}', function($name) {
    echo json_encode(["message" => "Hello, $name"]);
});

Router::post('/example', function(array $data) {
    echo json_encode(["message" => "Exemplo criado com sucesso", "data" => $data]);
});
