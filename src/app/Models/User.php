<?php
declare(strict_types=1);
namespace App\Models;

class User extends Model {
    protected string $table = "accounts";

    /** 
     * @param array{username: string, password: string} $login
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function login(array $login): array {
        $this->builder->table($this->table);
        $this->builder->where('username', $login['username']);
        /** @var array<int, array{id: int, email: string, username: string, password: string}> */
        return $this->builder->select('fetch');
    }

    /**
     * @param array{email: string, password: string, username: string} $insert
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function register(array $insert): array {
        $this->builder->table($this->table);
        /** @var array{status: 'success', result: mixed} | array{status: 'error', message: string} */
        return $this->builder->insert($insert);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function create(string $username, string $email, string $password): array {
        $this->builder->table($this->table);
        /** @var array{status: 'success', result: mixed} | array{status: 'error', message: string} */
        return $this->builder->insert(['username' => $username, 'email' => $email, 'password' => $password]);
    }

    /**
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function index(): array {
        $this->builder->table($this->table);
        $this->builder->columns(['id', 'username', 'email', 'created_at']);
        /** @var array<int, array{id: int, email: string, username: string, password: string}> */
        return $this->builder->select();
    }

    /**
     * @param string $id
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function show(string $id): array {
        $this->builder->table($this->table);
        $this->builder->columns(['id', 'username', 'email', 'created_at']);
        $this->builder->where('id', $id);
        /** @var array<int, array{id: int, email: string, username: string, password: string}> */
        return $this->builder->select('fetch');
    }

    /**
     * @param string $id
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function delete(string $id): array {
        $this->builder->table($this->table);
        $this->builder->where('id', $id);
        /** @var array<int, array{id: int, email: string, username: string, password: string}> */
        return $this->builder->delete();
    }

    /**
     * @param string $id
     * @param array{email: string, password: string, username: string} $request
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function update(string $id, array $request): array {
        $this->builder->table($this->table);
        $this->builder->where('id', $id);
        $columns_allowed = array_flip(['username', 'email', 'password']);
        $update = array_intersect_key($request, $columns_allowed);
        array_walk($update, function (&$value, $column) {
            if ($column === 'password') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
        });
        return $this->builder->update($update);
    }
}