<?php
require_once './vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . './core/env.php';
require $_SERVER['DOCUMENT_ROOT'] . './core/logs.php';
header("Access-Control-Allow-Origin: *"); // Permite qualquer origem. Você pode especificar uma URL aqui se preferir.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}


use Core\Application;

$app = new Application();
$app->run();
