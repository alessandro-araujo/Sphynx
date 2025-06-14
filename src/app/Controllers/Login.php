<?php
declare(strict_types=1);
namespace App\Controllers;

use App\DTO\User\{UserRegister as UserRegisterDTO, UserAuth as UserAuthDTO};
use App\Helpers\{Request, Result, JWT};
use App\Models\User as UserModel;
use App\Services\User as UserService;
use Database\InlineSQL;
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
            if (!Request::required($request, ['password', 'username'])) $this->response(
                $this->lang->get('error.not_provided_s.username_password'), 400);

            $user_object = UserAuthDTO::set($request);
            $user_model = new UserModel($args['connection']);
            $user_service = new UserService($user_model);

            /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, password: string}} $user */
            $user = $user_service->login($user_object);

            if (!Result::status($user)) {
                assert(isset($user['message']));
                $this->response($this->lang->get("error.{$user['message']}.user"), 401);
            }

            assert(isset($user['result']));
            if (!Result::password($user_object->password, $user['result']['password'])) $this->response(
                $this->lang->get('error.invalid.username_password'), 401);

            $this->response(['message' => $this->lang->get('success.successful.login')['success'],
            'token' => JWT::create($user['result'])], 200);

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

        $user_object = UserRegisterDTO::set($request);
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