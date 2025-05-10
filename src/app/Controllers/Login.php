<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\JWTHandler;

class Login extends Controller {
    /**
    * @param array{email: string, password: string} $request
    * @param array{connection: \Database\InlineSQL} $args
    */
    public function login(array $request, array $args): void {
        if (empty($request['email']) || empty($request['password'])) $this->response(["error" => "E-mail ou senha não fornecidos"], 400);

        $user_model = new User($args['connection']);
        $user = $user_model->login($request['email']);

        /** @var array{error?: bool, message?: string} $user */
        if (isset($user['error'])) {
            assert(isset($user['message']));
            $this->response(["error" => $user['message'] . " de Login"], 401);
        }
        
        /** @var array{id: int, email: string, username: string, password: string} $user */
        if (!password_verify($request['password'], $user['password'])) $this->response(["error" => "E-mail ou senha inválidos"], 401);
        
        $jwtHandler = new JWTHandler();
        $payload = [
            'sub' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username']
        ];
        
        try {
            $jwt = $jwtHandler->gerarToken($payload);
            $this->response(["message" => "Login bem-sucedido!", "token" => $jwt], 200);
        } catch (\Exception $error) {
            if (($_ENV['APP_ENV'] == 'development') AND ($_ENV['APP_DEBUG'] == 'True')) $this->response(["error" => $error->getCode() .' '. $error->getMessage()], 401);
            $this->response(["error" => "Ocorreu um erro na autenticação: "], 401);
        }
    }
}
