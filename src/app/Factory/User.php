<?php
namespace App\Factory;
use App\DTO\User as UserObject;

final class User
{
    /**
     * @param array{email: string, password: string, username: string} $request
     */
    public static function user(array $request): UserObject {
        $ar_columns = ['email' => null, 'password' => null, 'username' => null];
        return new UserObject(...array_values($request + $ar_columns));
    }
}
