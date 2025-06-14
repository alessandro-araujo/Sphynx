<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\User as UserModel;
use Database\InlineSQL;
use JetBrains\PhpStorm\NoReturn;
use Exception;

class User extends Controller {
    /**
     * @param array<never, never> $request
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function index(array $request, array $args): void {
        if (!empty($request)) $this->response($this->lang->get('error.not_allowed_s.parameters'), 401);

        $user_model = new UserModel($args['connection']);
        /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, created_at: string}} $user */
        $user = $user_model->index();

        if (empty($user['result'])) $this->response(
            $this->lang->get('error.invalid.email_password'), 401);

        /** @var array{result: array{id: int, email: string, username: string, created_at: string}} $user */
        $this->response(['result' => $user['result']], 201);
    }

    /**
     * @param array<never, never> $request
     * @param string $id
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function show(array $request, string $id, array $args): void {
        try {
            if (empty($id)) $this->response(
                $this->lang->get('error.not_provided.email_password'), 400);

            if (!empty($request)) $this->response($this->lang->get('error.not_allowed_s.parameters'), 401);

            $user_model = new UserModel($args['connection']);
            /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, created_at: string}}  $user */

            $user = $user_model->show($id);

            if (empty($user['result'])) $this->response(
                $this->lang->get('message.not_found.user'), 404);

            if ($user['status'] === 'error') {
                assert(isset($user['message']));
                $this->response(["error" => $user['message']], 401);
            }

            /** @var array{result: array{id: int, email: string, username: string, created_at: string}} $user */
            $this->response(['result' => $user['result']], 200);
        } catch (Exception $error) {
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
    #[NoReturn] public function create(array $request, array $args): void {
        if (empty($request['email']) || empty($request['password']) || empty($request['username'])) $this->response(
            $this->lang->get('error.not_provided.email_password_username'), 400);

        $user_model = new UserModel($args['connection']);

        /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, password: string}} $user */
        $user = $user_model->create($request['username'], $request['email'],  password_hash($request['password'],
            PASSWORD_DEFAULT));
        $user_status = $user['status'];

        if ($user_status === 'error') {
            assert(isset($user['message']));
            $this->response(["error" => $user['message'] . " de Login"], 401);
        }

        $this->response(['message' => $this->lang->get('success.successful.register')['success']],
            201);
    }

    /**
     * @param array<never, never> $request
     * @param string $id
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function delete(array $request, string $id, array $args): void {
        if (empty($id)) $this->response(
            $this->lang->get('error.not_provided.email_password'), 400);
        if (!empty($request)) $this->response($this->lang->get('error.not_allowed_s.parameters'), 401);

        $user_model = new UserModel($args['connection']);
        /** @var array{status: string, message?: string, result?: array{id: int, email: string, username: string, created_at: string}} $user */
        $user = $user_model->delete($id);

        if (isset($user['result'])) $this->response(
            $this->lang->get('message.not_found.user'), 404);
            
        /** @var array{result: array{id: int, email: string, username: string, created_at: string}} $user */
        $this->response(['result' => $user['result']], 200);
    }

    /**
     * @param array{email: string, password: string, username: string} $request
     * @param string $id
     * @param array{connection: InlineSQL} $args
     * @return void
     */
    #[NoReturn] public function update(array $request, string $id, array $args): void {
        $fields = array_filter($request, fn($parameters) => !empty($parameters));
        if (empty($fields)) $this->response(
            $this->lang->get('error.not_provided.email_password_username'), 400);

        $user_model = new UserModel($args['connection']);
        $user = $user_model->update($id, $request);
        $user_status = $user['status'];

        if ($user_status === 'error') {
            assert(isset($user['message']));
            $this->response(["error" => $user['message'] . " for update"], 401);
        }

        if ($user['status'] === 'success' && $user['result'] === 0) $this->response(
            $this->lang->get('error.not_provided.email_password_username'), 204);

        $this->response(['message' => $this->lang->get('success.updated.user')['success']],
            201);
    }
}