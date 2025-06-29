<?php
declare(strict_types=1);
namespace App\Helpers;

use JetBrains\PhpStorm\NoReturn;

class Response {
    /**
     * Method to return a JSON response with HTTP status code.
     * @param array<string, int|string> $data The content of the response.
     * @param int $status_code The HTTP status code.
     * @return void
     */
    #[NoReturn] public function response(array $data, int $status_code): void {
        http_response_code($status_code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
