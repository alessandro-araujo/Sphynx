<?php
namespace Core;

class Application {
    public function __construct() {
        require __DIR__ . '/../routes/Router.php';
    }
}
