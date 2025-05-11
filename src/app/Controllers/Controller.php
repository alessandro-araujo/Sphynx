<?php

namespace App\Controllers;

use JetBrains\PhpStorm\NoReturn;
class Controller {
    /**
     * Method to return a JSON response with HTTP status code.
     *
     * @param array<string> $data The content of the response.
     * @param int $statusCode The HTTP status code.
     * @return void
     */
    public function response(array $data, int $statusCode): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
