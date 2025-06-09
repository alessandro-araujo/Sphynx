<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\User as UserModel;
use App\Helpers\{JWTHandler, Request, Result};
use App\Services\User as UserService;
use App\Factory\User as UserFactory;
use Database\InlineSQL;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use TypeError;

class Login extends Controller {
    /**
     * @param array{username: string, password: string} $request
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    public function login(array $request, array $args): void {
        try {
            if ((empty($request['username'])) OR (empty($request['password']))) $this->response(
                $this->lang->get('error.not_provided_s.username_password'), 400);
            $user_model = new UserModel($args['connection']);
            /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, password: string}} $user */
            $user = $user_model->login($request['username']);

            if ($user['status'] === 'error') {
                assert(isset($user['message']));
                $this->response(["error" => $user['message'] . " de Login"], 401);
            }

            if (empty($user['result']['password'])) $this->response(
                $this->lang->get('error.invalid.username_password'), 401); else $login = $user['result'];
            assert(isset($login));

            /** @var array{id: int, email: string, username: string, password: string} $login */
            if (!password_verify($request['password'], $login['password'])) $this->response(
                $this->lang->get('error.invalid.username_password'), 401);

            unset($request);

            $jwtHandler = new JWTHandler();
            $payload = [
                'sub' => $login['id'],
                'email' => $login['email'],
                'username' => $login['username']
            ];

            try {
                $jwt = $jwtHandler->gerarToken($payload);
                $this->response(['message' => $this->lang->get('success.successful.login')['success'], 'token' => $jwt],
                    200);
            } catch (Exception $error) {
                if (($_ENV['APP_ENV'] == 'development') AND ($_ENV['APP_DEBUG'] == 'True')) {
                    $this->response(["error" => $error->getCode() .' '. $error->getMessage()], 401);
                }
                $this->response(['message' => $this->lang->get('error.authentication.login')['error'], 'token' => 'false'],
                    401);
            }
        } catch (TypeError $error) {
            $this->response([
                'error' => $this->lang->get('error.type_error.parameters')['error'],
                'message' => $error->getMessage(),
                'file' => $error->getFile(),
                'row' => $error->getLine()
            ], 400);
        }
    }

    /**
     * @param array{email: string, password: string, username: string} $request
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function register(array $request, array $args): void {
        if (!Request::required($request, ['email', 'password', 'username'])) $this->response(
            $this->lang->get('error.not_provided_s.email_password_username'), 400);

        $user_object = UserFactory::user($request);
        $user_model = new UserModel($args['connection']);
        $user_service = new UserService($user_model);
        /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, password: string}} $user */
        $user = $user_service->register($user_object);

        if (!Result::status($user)) {
            assert(isset($user['message']));
            $this->response($this->lang->get("error.{$user['message']}.login"), 400);
        }

        $this->response(['message' => $this->lang->get('success.successful.register')['success']],
            201);
    }
}