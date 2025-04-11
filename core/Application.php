<?php
namespace Core;
use Core\Router;

class Application {
    public function run() {
        $router = new Router();
        $router->handleRequest();
    }
}
