<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Helpers\Request;
use App\DTO\User\{UserCreate as UserCreateDTO, UserUpdate as UserUpdateDTO};
use App\Helpers\Result;
use App\Models\User as UserModel;
use App\Services\User as UserService;
use Database\InlineSQL;
use JetBrains\PhpStorm\NoReturn;

class User extends Controller {
    /**
     * GET
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function index(array $args): void {
        $user_model = new UserModel($args['connection']);
        /** @var array{status: string, message?: string,
         *     result?: array{id: int, email: string, username: string, created_at: string}} $user */
        $user = $user_model->index();

        Result::status($user, 'users', 401);
        Result::empty($user, 'not_found.users', 401);

        /** @var array{result: array{id: int, email: string, username: string, created_at: string}} $user */
        $this->response(['status' => 200, 'result' => $user['result']], 200);
    }

    /**
     * GET
     * @param int $id
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function show(int $id, array $args): void {
        $user_model = new UserModel($args['connection']);
        /** @var array{status: string, message?: string,
         *     result?: array{id: int, email: string, username: string, created_at: string}}  $user */
        $user = $user_model->show($id);

        Result::status($user, 'user', 401);
        Result::empty($user, 'not_found.user', 401);

        assert(isset($user['result']));
        /** @var array{result: array{id: int, email: string, username: string, created_at: string}} $user */
        $this->response(['status' => 200, 'result' => $user['result']], 200);
    }

    /**
     * GET
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function profile(array $args): void {

        $user_model = new UserModel($args['connection']);
        $user_service = new UserService($user_model);

        /** @var array{status: string, message?: string,
         *     result?: array{id: int, email: string, username: string, created_at: string}}  $user */
        $user = $user_service->profile();

        Result::status($user, 'user', 401);
        Result::empty($user, 'not_found.user', 401);

        assert(isset($user['result']));
        /** @var array{result: array{id: int, email: string, username: string, created_at: string}} $user */
        $this->response(['status' => 200, 'result' => $user['result']], 200);
    }

    /**
     * POST
     * @param array{email: string, password: string, username: string} $request
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function create(array $request, array $args): void {
        Request::required($request, ['email', 'password', 'username'], 'email_password_username');

        $user_object = UserCreateDTO::set($request);
        $user_model = new UserModel($args['connection']);
        $user_service = new UserService($user_model);
        /** @var array{status: string, message?: string,
         *     result?: array{id: int, email: string, username: string, password: string}} $user */
        $user = $user_service->create($user_object);

        Result::status($user, 'login', 400);

        $this->response(['status' => 201, 'message' => $this->lang->get('success.successful.register')['success']],
            201);
    }

    /**
     * DELETE
     * @param int $id
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function delete(int $id, array $args): void {
        Request::requiredParam($id, 'id');

        $user_model = new UserModel($args['connection']);
        /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, created_at: string}} $user */
        $user = $user_model->delete($id);

        Result::status($user, 'user', 404);

        /** @var array{result: array{id: int, email: string, username: string, created_at: string}} $user */
        $this->response(['result' => $user['result']], 204);
    }

    /**
     * PATCH
     * @param array{email: string, password: string, username: string} $request
     * @param int $id
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function update(array $request, int $id, array $args): void {
        $user_object = UserUpdateDTO::set($request);
        $user_model = new UserModel($args['connection']);
        $user_service = new UserService($user_model);
        $user = $user_service->update($user_object, $id);

        Result::status($user, 'user', 401);

        $this->response(['status' => 200, 'message' => $this->lang->get('success.updated.user')['success']],
            200);
    }
}