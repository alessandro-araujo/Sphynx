<?php

namespace App\Models;
use App\Interfaces\Database;

/**
 * Classe Model
 * Classe base para modelos de dados
 */
abstract class Model {
    protected Database $builder;
    
    /**
     * Construtor da classe Model
     */
    public function __construct(Database $database) {
        $this->builder = $database;
    }
}