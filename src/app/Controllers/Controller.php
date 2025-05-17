<?php

namespace App\Controllers;

use App\Language\Lang;
use JetBrains\PhpStorm\NoReturn;
class Controller {
    protected Lang $lang;
    public function __construct() {
        $this->lang = new Lang();
    }

    /**
     * Method to return a JSON response with HTTP status code.
     * @param array<string> $data The content of the response.
     * @param int $statusCode The HTTP status code.
     * @return void
     */
    #[NoReturn] protected function response(array $data, int $statusCode): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
