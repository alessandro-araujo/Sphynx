<?php

namespace App\Models;

class User extends Model
{
    protected $table = 'accounts';

    public function findByEmail($email)
    {
        return $this->db->table($this->table)
            ->where('email', $email)
            ->first();
    }

    public function findById($id)
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->first();
    }
}
