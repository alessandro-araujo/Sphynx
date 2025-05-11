<?php

namespace App\Models;
use App\Interfaces\Database;

/**
 * Class Model
 * Base class for data models
 */
abstract class Model {
    protected Database $builder;
    
    /**
     * Model class constructor
     */
    public function __construct(Database $database) {
        $this->builder = $database;
    }
}