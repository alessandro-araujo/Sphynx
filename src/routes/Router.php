<?php

use Core\Router;
use App\Controllers\Login;
use App\Controllers\User;
use App\Middleware\AuthMiddleware;
use Database\InlineSQL;

$connection_database = new InlineSQL();
$args = ['connection' => $connection_database];
/** Routes Login */
Router::post('/login', [Login::class, 'login'], null, $args);
Router::post('/register', [Login::class, 'register'], null, $args);
/** Routes Users */
Router::post('/users', [User::class, 'create'], AuthMiddleware::class, $args);
Router::get('/users', [User::class, 'index'], AuthMiddleware::class, $args);
Router::get('/users/profile', [User::class, 'profile'], AuthMiddleware::class, $args);
Router::get('/users/{id}', [User::class, 'show'], AuthMiddleware::class, $args);
Router::delete('/users/{id}', [User::class, 'delete'], AuthMiddleware::class, $args);
Router::patch('/users/{id}', [User::class, 'update'], AuthMiddleware::class, $args);
/** Routes Statics */
Router::get('/examples', function() {
    echo json_encode(["status" => 200, "message" => "Hello, World!"]);
}, AuthMiddleware::class);
Router::get('/examples/{name}', function(string $name)  {
    echo json_encode(["status" => 200, "message" => "Hello, $name"]);
}, AuthMiddleware::class);
Router::post('/examples', function(array $data) {
    echo json_encode(["status" => 200, "message" => "Example created successfully", "data" => $data]);
}, AuthMiddleware::class);
