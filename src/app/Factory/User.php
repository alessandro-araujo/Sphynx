<?php
namespace App\Factory;
use App\DTO\User as UserObject;

final class User
{
    /**
     * @param array{email: string, password: string, username: string} $request
     */
    public static function user(array $request): UserObject {
        $columns_request = ['email' => 'email', 'username' => 'username', 'password' => 'password'];
        return new UserObject(...array_map(fn($params) => $request[$columns_request[$params]],
            array_keys($columns_request)
        ));

    }
}
