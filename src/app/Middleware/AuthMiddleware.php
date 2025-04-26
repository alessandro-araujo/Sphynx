<?php

namespace App\Middleware;

use App\Helpers\JWTHandler;
use Exception;

class AuthMiddleware
{
    public function handle(callable $next)
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            http_response_code(401);
            echo json_encode(['error' => 'Token não informado']);
            exit;
        }

        $token = str_replace('Bearer ', '', $authHeader);
        $jwt = new JWTHandler();

        try {
            $dados = $jwt->validarToken($token);
            // Passa os dados do usuário para o handler final (se quiser)
            return $next($dados);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
