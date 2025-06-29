<?php
declare(strict_types=1);
namespace App\Mappers;
use App\DTO\User\{UserRegister as UserRegisterDTO, UserAuth as UserAuthDTO, UserCreate as UserCreateDTO,
    UserUpdate as UserUpdateDTO};

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
     * @param UserCreateDTO $user_object
     * @return array{email: string, password: string, username: string}
     */
    public static function create(UserCreateDTO $user_object): array {
        return [
            'email' => $user_object->email,
            'password' => $user_object->password,
            'username' => $user_object->username
        ];
    }

    /**
     * @param UserUpdateDTO $user_object
     * @return array<'address'|'email'|'number'|'password'|'username', mixed>
     */
    public static function update(UserUpdateDTO $user_object): array {
        $columns_allowed = array_flip(['username', 'email', 'password', 'number', 'address']);
        $update = get_object_vars($user_object);
        return array_intersect_key($update, $columns_allowed);
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
