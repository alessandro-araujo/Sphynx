<?php

namespace App\Models;

class User extends Model {
    protected string $table = "accounts";

    /** 
     * @param string $email
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function login(string $email): array {
        $this->builder->table($this->table);
        $this->builder->where('email', $email);
        /** @var array<int, array{id: int, email: string, username: string, password: string}> */
        return $this->builder->select('fetch');
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function register(string $username, string $email, string $password): array {
        $this->builder->table($this->table);
        /** @var array{status: 'success', result: mixed} | array{status: 'error', message: string} */
        return $this->builder->insert(['username' => $username, 'email' => $email, 'password' => $password]);
    }
}