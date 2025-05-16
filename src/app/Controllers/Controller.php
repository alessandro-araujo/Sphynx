<?php

namespace App\Controllers;

use JetBrains\PhpStorm\NoReturn;
use App\Language\Lang;
class Controller {
    protected Lang $lang;
    public function __construct() {
        $this->lang = new Lang();
    }

    /**
     * Method to return a JSON response with HTTP status code.
     *
     * @param array<string> $data The content of the response.
     * @param int $statusCode The HTTP status code.
     * @return void
     */
    #[NoReturn] public function response(array $data, int $statusCode): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
