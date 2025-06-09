<?php

namespace App\DTO;

final class User {
    public function __construct(
        public ?string $email,
        public ?string $password,
        public ?string $username,
    ) {}
}
