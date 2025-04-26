<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTHandler
{
    private string $secret;
    private string $algoritmo = 'HS256';  

    public function __construct()
    {
        if (empty($_ENV['JWT_SECRET_KEY'])) {
            throw new Exception('A chave secreta do JWT não foi definida.');
        }
        $this->secret = $_ENV['JWT_SECRET_KEY'];
    }

    public function gerarToken(array $payload): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $_ENV['JWT_TTL'] ?? 3600; // Tempo de expiração padrão de 1 hora
        return JWT::encode($payload, $this->secret, $this->algoritmo);
    }

    public function validarToken(string $jwt): array
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret, $this->algoritmo));
            return (array) $decoded;
        } catch (Exception $e) {
            throw new Exception('Token inválido ou expirado: ' . $e->getMessage());
        }
    }
}
