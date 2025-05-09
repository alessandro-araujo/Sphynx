<?php

namespace App\Models;

class User extends Model
{
    protected string $table = "accounts";

    public function login($email): array 
    {
        $this->builder->table($this->table);
        $this->builder->where('email', $email);
        return $this->builder->select('fetch');
    }

    // public function get($id = null, $name = null, $age = null): array
    // {
    //     $this->builder->table($this->table);
    //     $this->builder->where('id', 1);
    //     $this->builder->orwhere('name', "anything OR '1'='1'");
    //     $this->builder->where('name', 'Alessandro');
    //     return $this->builder->select();

    //     // $this->builder->columns(['name']);
    //     // $this->builder->Where('id', 2);
    //     // $this->builder->orwhere('name', 'Alessandro');
    //     // $this->builder->where('name', "' OR '1'='1");

    //     # $this->builder->columns(['users.id', 'users.nome', 'users.idade', 'posts.titulo']); # Define as colunas a serem selecionadas
    //     # $this->builder->join('posts', 'users.id', 'posts.user_id'); # Adiciona um JOIN com a tabela posts
    // }
    // public function findByEmail($email)
    // {
    //     return $this->db->table($this->table)
    //         ->where('email', $email)
    //         ->first();
    // }

    // public function findById($id)
    // {
    //     return $this->db->table($this->table)
    //         ->where('id', $id)
    //         ->first();
    // }
}
