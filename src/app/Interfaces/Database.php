<?php

namespace App\Interfaces;

use PDO;

/**
 * Interface Database
 * Define a estrutura básica para classes de acesso a banco de dados
 */
interface Database {
    /**
     * @param string $config_search
     * @param int $config_param (default: PDO::FETCH_ASSOC)
     * @return array<int, array<string, mixed>> The result set as an associative array
     */
    public function select(string $config_search = 'fetchAll', int $config_param = PDO::FETCH_ASSOC): array;

    /**
     * @param  array<int, array<string, mixed>> $columns
     * @return self
     */
    public function columns(array $columns): self;

    /** PARAMETROS DE CONFIGURAÇÃO
     * @param string $field
     * @param string $field
     * @param string $value
     * @param string $operator
     * @return self
     */
    public function where(string $field, string $value, string $operator = '='): self;
    public function orWhere(string $field, string $value, string $operator = '='): self;
    public function table(string $table): self;
    public function join(string $table, string $firstField, string $secondField, string $joinType = 'INNER'): self;
}