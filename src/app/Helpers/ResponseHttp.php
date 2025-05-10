<?php

namespace App\Helpers;


class ResponseHttp
{
    /**
     * Método para retornar uma resposta JSON com código de status HTTP.
     *
     * @param array<string> $data O conteúdo da resposta.
     * @param int $statusCode O código de status HTTP.
     * @return void
     */
    public function response(array $data, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
