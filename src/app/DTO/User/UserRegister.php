<?php
declare(strict_types=1);
namespace App\DTO\User;

final class UserRegister {
    public function __construct(
        public string $email,
        public string $username,
        public string $password,
    ) {}

    /**
     * @param array{email: string, username: string, password: string} $request
     * @return UserRegister
     */
    public static function set(array $request): UserRegister {
        return new self(
            email: $request['email'],
            username: $request['username'],
            password: $request['password']
        );
    }
}
