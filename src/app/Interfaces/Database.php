<?php

namespace App\Interfaces;

use PDO;

/**
 * Interface Database
 * Defines the basic structure for database access classes
 */
interface Database {
    /**
     * @param string $config_search
     * @param int $config_param (default: PDO::FETCH_ASSOC)
     * @return array{status: 'success', result: array<int, array<string, mixed>>} | array{status: 'error', message: string}
     */
    public function select(string $config_search = 'fetchAll', int $config_param = PDO::FETCH_ASSOC): array;

    /**
     * @param array<int, string> $columns
     * @return self
     */
    public function columns(array $columns): self;

    /** PARAMETROS DE CONFIGURAÇÃO
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