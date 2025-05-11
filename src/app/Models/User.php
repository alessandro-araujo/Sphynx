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
        return (array) $this->builder->select('fetch');
    }
}