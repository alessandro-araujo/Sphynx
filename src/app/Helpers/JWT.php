<?php

namespace App\Helpers;

final class JWT {
    /**
     * @param array{id: int|string, email: string, username: string} $login
     * @return string
     */
    public static function create(array $login): string {

        $jwt = new JWTHandler();
        $payload = [
            'sub' => $login['id'],
            'email' => $login['email'],
            'username' => $login['username']
        ];
        return $jwt->gerarToken($payload);
    }
}
