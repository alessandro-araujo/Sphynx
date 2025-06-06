<?php

namespace App\Middleware;

use App\Helpers\JWTHandler;
use Exception;

class AuthMiddleware {

    /**
     * Method to verify the JWT token in the Authorization header.
     * @param callable $next The next handler to be called.
     * @return mixed
     */
    public function handle(callable $next) {
        $headers = getallheaders();
        if (empty($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Token não informado']);
            exit;
        }
        $authHeader = $headers['Authorization'];

        if (!is_string($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['error' => 'Token não informado']);
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $jwt = new JWTHandler();

        try {
            $dados = $jwt->validarToken($token);
            return $next($dados);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
