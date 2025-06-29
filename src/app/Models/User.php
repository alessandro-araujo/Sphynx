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
        $this->builder->returnInsert(true);
        /** @var array{status: 'success', result: mixed} | array{status: 'error', message: string} */
        return $this->builder->insert($insert);
    }

    /**
     * @param array{email: string, password: string, username: string} $insert
     * @return array{status: 'success', result: mixed} | array{status: 'error', message: string}
     */
    public function create(array $insert): array {
        $this->builder->table($this->table);
        /** @var array{status: 'success', result: mixed} | array{status: 'error', message: string} */
        return $this->builder->insert($insert);
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
     * @param int $id
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function show(int $id): array {
        $this->builder->table($this->table);
        $this->builder->columns(['id', 'username', 'email', 'created_at']);
        $this->builder->where('id', $id);
        /** @var array<int, array{id: int, email: string, username: string, password: string}> */
        return $this->builder->select('fetch');
    }

    /**
     * @param int $id
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function delete(int $id): array {
        $this->builder->table($this->table);
        $this->builder->where('id', $id);
        /** @var array<int, array{id: int, email: string, username: string, password: string}> */
        return $this->builder->delete();
    }

    # @return array{status: 'success', result: string} | array{status: 'error', message: string}
    /**
     * @param int $id
     * @param array<'address'|'email'|'number'|'password'|'username', mixed> $update
     * @return array{status: 'error', message: string}|array{status: 'success', result: mixed}
     */
    public function update(int $id, array $update): array {
        $this->builder->table($this->table);
        $this->builder->where('id', $id);
        return $this->builder->update($update);
    }
}