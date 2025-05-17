<?php
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../core/Functions/env.php';
require __DIR__ . '/../core/Functions/logs.php';
require __DIR__ . '/../core/Functions/dd.php';



# ini_set('display_errors', ($_ENV['APP_ENV'] === 'development' && $_ENV['APP_DEBUG'] === 'True') ? '1' : '0');
ini_set('display_errors','1');
ini_set('log_errors', '1');
# error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('HTTP/1.1 200 OK');
    exit;
}

use Core\Application;

new Application();
