<?php
declare(strict_types=1);
namespace App\Services;

use App\DTO\User\{UserAuth as UserAuthDTO, UserRegister as UserRegisterDTO};
use App\Mappers\User as UserMapper;
use App\Models\User as UserModel;

final readonly class User {
    public function __construct(private UserModel $user_model){}

    /**
     * @param UserRegisterDTO $user_object
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function register(UserRegisterDTO $user_object): array {
        $user_object->password = $this->hashPassword($user_object->password);
        $insert = UserMapper::register($user_object);
        return $this->user_model->register($insert);
    }
    /**
     * @param string $password
     * @return string
     */
    private function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    /**
     * @param UserAuthDTO $user_object
     * @return array<int, array{id: int, email: string, username: string, password: string}>
     */
    public function login(UserAuthDTO $user_object): array {
        $login = UserMapper::login($user_object);
        return $this->user_model->login($login);
    }
}
