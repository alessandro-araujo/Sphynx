<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTHandler
{
    private string $secret;
    private string $algorithm = 'HS256';

    public function __construct()
    {
        if (empty($_ENV['JWT_SECRET_KEY'])) {
            throw new Exception('A chave secreta do JWT não foi definida.');
        }
        // @phpstan-ignore assign.propertyType
        $this->secret = $_ENV['JWT_SECRET_KEY'];
    }

    // @phpstan-ignore missingType.iterableValue
    public function gerarToken(array $payload): string
    {
        $time =  $_ENV['JWT_TTL'] ?? 3600;
        $payload['iat'] = time();
        // @phpstan-ignore binaryOp.invalid
        $payload['exp'] = time() + $time;
        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    // @phpstan-ignore missingType.iterableValue
    public function validarToken(string $jwt): array
    {
        try {
            $decoded = JWT::decode($jwt, new Key($this->secret, $this->algorithm));
            return (array) $decoded;
        } catch (Exception $e) {
            throw new Exception('Token inválido ou expirado: ' . $e->getMessage());
        }
    }
}
