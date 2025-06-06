<?php

use Core\Router;
use App\Controllers\Login;
use App\Controllers\User;
use App\Middleware\AuthMiddleware;
use Database\InlineSQL;

$args = ['connection' => new InlineSQL()];

/** Routes Login */
Router::post('/login', [Login::class, 'login'], null, $args);
Router::post('/register', [Login::class, 'register'], null, $args);

/** Routes Users */
Router::post('/user', [User::class, 'create'], AuthMiddleware::class, $args);
Router::get('/user', [User::class, 'index'], AuthMiddleware::class, $args);
Router::get('/user/{id}', [User::class, 'show'], AuthMiddleware::class, $args);
Router::delete('/user/{id}', [User::class, 'delete'], AuthMiddleware::class, $args);
Router::put('/user/{id}', [User::class, 'update'], AuthMiddleware::class, $args);


# Routes Statics
Router::get('/example', function() {
    echo json_encode(["message" => "Hello, World!"]);
});
Router::get('/example/{name}', function(string $name)  {
    echo json_encode(["message" => "Hello, $name"]);
});
Router::post('/example', function(array $data) {
    echo json_encode(["message" => "Example created successfully", "data" => $data]);
});
