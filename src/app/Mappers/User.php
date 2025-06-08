<?php
namespace App\Mappers;
use App\DTO\User as UserObject;

final class User {
    /**
     * @param UserObject $user_object
     * @return array{email: string, password: string, username: string}
     */
    public static function register(UserObject $user_object): array {
        return [
            'email' => $user_object->email,
            'password' => $user_object->password,
            'username' => $user_object->username
        ];
    }
}
