<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\JWTHandler;

class Login extends Controller
{
    public function login($data): void
    {
        if (empty($data['email']) || empty($data['password'])) {
            $this->response(["error" => "E-mail ou senha não fornecidos"], 400);
            return;
        }
        
        $userModel = new User();
        $user = $userModel->findByEmail($data['email']);
        
        if (!$user) {
            $this->response(["error" => "E-mail ou senha inválidos"], 401);
            return;
        }
        
        if (!password_verify($data['password'], $user->password)) {
            $this->response(["error" => "E-mail ou senha inválidos"], 401);
            return;
        }
        
        $jwtHandler = new JWTHandler();
        
        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'username' => $user->username
        ];
        
        try {
            $jwt = $jwtHandler->gerarToken($payload);
            $this->response([
                "success" => true,
                "message" => "Login bem-sucedido!",
                "token" => $jwt
            ], 200);
        } catch (\Exception $e) {
            $this->response(["error" => "Erro ao gerar o token: " . $e->getMessage()], 500);
        }
    }
}
