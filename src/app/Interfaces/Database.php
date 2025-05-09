<?php

namespace App\Interfaces;

use PDO;

/**
 * Interface Database
 * Define a estrutura básica para classes de acesso a banco de dados
 */
interface Database
{
    public function select(string $config_search = 'fetchAll', string $config_param = PDO::FETCH_ASSOC): array;
    public function columns(array $columns): self;
    public function where(string $field, $value, string $operator = '='): self;
    public function orWhere(string $field, $value, string $operator = '='): self;
    public function table(string $table): self;
    public function join(string $table, string $firstField, string $secondField, string $joinType = 'INNER'): self;
}