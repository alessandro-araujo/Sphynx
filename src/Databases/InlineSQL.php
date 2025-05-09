<?php

namespace Database;

use App\Interfaces\Database;
use PDO;
use PDOException;
use Exception;

class InlineSQL implements Database
{
    protected string $table = '';
    protected string $columns = '*';
    protected array $all_conditions = [];
    protected array $joins = [];
    protected array $params = [];

    protected PDO $pdo;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT            => 10,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            if ($_ENV['APP_ENV'] === 'production') throw new Exception("Erro ao conectar ao banco de dados.");
            throw new Exception("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function table(string $table): self
    {
        $this->table = trim($table);
        return $this;
    }

    public function columns(array $columns): self
    {
        $this->columns = implode(", ", array_map('trim', $columns));
        return $this;
    }

    public function where(string $field, $value, string $operator = '='): self
    {
        $this->all_conditions[] = [
            "type" => "AND",
            "condition" => $this->prepareCondition($field, $value, $operator),
        ];
        return $this;
    }

    public function orWhere(string $field, $value, string $operator = '='): self
    {
        $this->all_conditions[] = [
            "type" => "OR",
            "condition" => $this->prepareCondition($field, $value, $operator),
        ];
        return $this;
    }

    public function join(string $table, string $firstField, string $secondField, string $joinType = 'INNER'): self
    {
        $joinType = strtoupper($joinType);
        $validTypes = ['INNER', 'LEFT', 'RIGHT', 'FULL', 'CROSS'];

        if (!in_array($joinType, $validTypes)) throw new Exception("JOIN inválido: $joinType");

        $this->joins[] = [
            'type' => $joinType,
            'table' => trim($table),
            'condition' => trim($firstField) . ' = ' . trim($secondField),
        ];

        return $this;
    }

    protected function prepareCondition(string $field, $value, string $operator): string
    {
        $paramKey = ':param_' . count($this->params);
        $this->params[$paramKey] = $value;
        return "{$field} {$operator} {$paramKey}";
    }

    public function select(string $config_search = 'fetchAll', string $config_param = PDO::FETCH_ASSOC): array
    {
        # var_dump($config);die; // Debugging line to check the config array
        if (empty($this->table)) {
            throw new Exception("Tabela não definida.");
        }

        $joinClause = '';
        foreach ($this->joins as $join) {
            $joinClause .= " {$join['type']} JOIN {$join['table']} ON {$join['condition']}";
        }

        $whereClause = '';
        foreach ($this->all_conditions as $index => $condition) {
            $prefix = $index === 0 ? 'WHERE' : $condition['type'];
            $whereClause .= " {$prefix} {$condition['condition']}";
        }

        $sql = "SELECT {$this->columns} FROM {$this->table}{$joinClause}{$whereClause}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            return $stmt->$config_search($config_param) ?: [];
        } catch (PDOException $e) {
            return ['error' => true, 'message' => 'Falha ao buscar dados: ' . $e->getMessage()];
        } finally {
            $this->reset();
        }
    }

    protected function reset(): void
    {
        $this->table = '';
        $this->columns = '*';
        $this->all_conditions = [];
        $this->joins = [];
        $this->params = [];
    }
}
