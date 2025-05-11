<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\JWTHandler;
use Database\InlineSQL;
use Exception;

class Login extends Controller {
    /**
    * @param array{email: string, password: string} $request
    * @param array{connection: InlineSQL} $args
     */
    public function login(array $request, array $args): void {
        if (empty($request['email']) || empty($request['password'])) $this->response(["error" => "E-mail ou senha não fornecidos"], 400);

        $user_model = new User($args['connection']);
        /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, password: string}} $user */
        
        $user = $user_model->login($request['email']);
        $user_status = $user['status'];

        if ($user_status === 'error') {
            assert(isset($user['message']));
            $this->response(["error" => $user['message'] . " de Login"], 401);
        }
        if (isset($user['result'])) $login = $user['result']; assert(isset($login));
        unset($user);

        /** @var array{id: int, email: string, username: string, password: string} $login */
        if (!password_verify($request['password'], $login['password'])) {
            $this->response(["error" => "E-mail ou senha inválidos"], 401);
        }

        unset($request);

        $jwtHandler = new JWTHandler();
        $payload = [
            'sub' => $login['id'],
            'email' => $login['email'],
            'username' => $login['username']
        ];
        
        try {
            $jwt = $jwtHandler->gerarToken($payload);
            $this->response(["message" => "Login bem-sucedido!", "token" => $jwt], 200);
        } catch (Exception $error) {
            if (($_ENV['APP_ENV'] == 'development') AND ($_ENV['APP_DEBUG'] == 'True')) $this->response(["error" => $error->getCode() .' '. $error->getMessage()], 401);
            $this->response(["error" => "Ocorreu um erro na autenticação: "], 401);
        }
    }
}