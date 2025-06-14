<?php
declare(strict_types=1);
namespace App\Mappers;
use App\DTO\User\{UserRegister as UserRegisterDTO, UserAuth as UserAuthDTO};

final class User {

    /**
     * @param UserRegisterDTO $user_object
     * @return array{email: string, password: string, username: string}
     */
    public static function register(UserRegisterDTO $user_object): array {
        return [
            'email' => $user_object->email,
            'password' => $user_object->password,
            'username' => $user_object->username
        ];
    }

    /**
     * @param UserAuthDTO $user_object
     * @return array{password: string, username: string}
     */
    public static function login(UserAuthDTO $user_object): array {
        return [
            'password' => $user_object->password,
            'username' => $user_object->username
        ];
    }
}
