<?php
declare(strict_types=1);
namespace App\DTO\User;

final class UserAuth {
    public function __construct(
        public string $username,
        public string $password
    ) {}

    /**
     * @param array{username: string, password: string} $request
     * @return UserAuth
     */
    public static function set(array $request): UserAuth {
        return new self(
            username: $request['username'],
            password: $request['password']
        );
    }
}
