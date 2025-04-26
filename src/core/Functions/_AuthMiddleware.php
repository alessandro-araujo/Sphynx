<?php

namespace App\Middleware;

use App\Helpers\JWTHandler;
use Exception;

class AuthMiddleware
{
    public function __invoke($request, $response, $next)
    {
        // Pega o cabeçalho Authorization
        $headers = $request->getHeaders();
        $authHeader = $headers['Authorization'][0] ?? '';

        if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
            return $response->withJson(['erro' => 'Token nao informado'], 401);
        }

        // Pega o token
        $token = str_replace('Bearer ', '', $authHeader);
        $jwt = new JWTHandler();

        try {
            // Valida o token
            $dados = $jwt->validarToken($token);
            // Adiciona os dados do token à requisição (por exemplo, o usuário logado)
            $request = $request->withAttribute('user', $dados);
        } catch (Exception $e) {
            return $response->withJson(['erro' => $e->getMessage()], 401);
        }

        // Prossegue para a próxima etapa do processamento da requisição
        return $next($request, $response);
    }
}
