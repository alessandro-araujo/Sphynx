<?php
declare(strict_types=1);
namespace App\Controllers;

use App\DTO\User\{UserRegister as UserRegisterDTO, UserAuth as UserAuthDTO};
use App\Helpers\{Request, Result, JWT};
use App\Models\User as UserModel;
use App\Services\User as UserService;
use Database\InlineSQL;
use JetBrains\PhpStorm\NoReturn;

class Login extends Controller {

    /**
     * @param array{username: string, password: string} $request
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function login(array $request, array $args): void {
        Request::required($request, ['password', 'username'], 'username_password');

        $user_object = UserAuthDTO::set($request);
        $user_model = new UserModel($args['connection']);
        $user_service = new UserService($user_model);
        /** @var array{status: string, message?: string,
         *     result?: array{id: int, email: string, username: string, password: string}} $user */
        $user = $user_service->login($user_object);

        Result::status($user, 'login', 401);
        Result::password($user_object->password, $user);

        assert(isset($user['result']));
        $this->response(['status' => 200, 'message' => $this->lang->get('success.successful.login')['success'],
            'token' => JWT::create($user['result'])], 200);
    }

    /**
     * @param array{email: string, password: string, username: string} $request
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function register(array $request, array $args): void {
        Request::required($request, ['email', 'password', 'username'], 'email_password_username');

        $user_object = UserRegisterDTO::set($request);
        $user_model = new UserModel($args['connection']);
        $user_service = new UserService($user_model);
        /** @var array{status: string, message?: string,
         *     result?: array{id: int, email: string, username: string, password: string}} $user */
        $user = $user_service->register($user_object);

        Result::status($user, 'login', 400);

        assert(isset($user['result']));
        $this->response(['status' => 201, 'message' => $this->lang->get('success.successful.register')['success'],
            'token' => JWT::create($user['result'])], 201);
    }
}