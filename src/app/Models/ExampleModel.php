<?php

namespace App\Models;

class ExampleModel extends Model
{
    protected $table;

    public function getData()
    {
        $this->table = 'comments';
        return $this->db->table($this->table)->get();
    }
}
