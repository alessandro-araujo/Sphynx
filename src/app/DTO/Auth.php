<?php


namespace App\DTO;

final class Auth
{
    public function __construct(
        public string $username,
        public string $password
    ) {}
}
