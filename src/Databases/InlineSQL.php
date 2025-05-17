<?php

namespace Database;

use App\Interfaces\Database;
use App\Helpers\ResponseHttp;
use PDO;
use PDOException;
use Exception;

class InlineSQL implements Database {
    protected string $table = '';
    protected string $columns = '*';
    /** @var array<int, array{type: string, table: string, condition: string}> */ protected array $joins = [];
    /** @var array<int, array{type: string, condition: string}> */ protected array $all_conditions = [];
    /** @var array<string> */ protected array $params = [];

    protected ResponseHttp $ResponseHttp;
    protected PDO $pdo;

    public function __construct() {
        $this->ResponseHttp = new ResponseHttp();
        $this->connect();
    }

    private function connect(): void {
        /** @var string $host */     $host = $_ENV['DB_HOST'];
        /** @var string $port */     $port = $_ENV['DB_PORT'];
        /** @var string $dbname */   $dbname = $_ENV['DB_DATABASE'];
        /** @var string $user */     $user = $_ENV['DB_USERNAME'];
        /** @var string $password */ $password = $_ENV['DB_PASSWORD'];

        # $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT            => 10,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $error) {
            if (($_ENV['APP_ENV'] == 'development') AND ($_ENV['APP_DEBUG'] == 'True')) $this->ResponseHttp->response(["error" => $error->getCode() .' '. $error->getMessage()], 500);
            $this->ResponseHttp->response(["error" => 'Erro ao conectar ao banco de dados'], 500);                     
        }
    }

    public function table(string $table): self {
        $this->table = trim($table);
        return $this;
    }

    public function columns(array $columns): self {
        $this->columns = implode(", ", array_map('trim', $columns));
        return $this;
    }

    public function where(string $field, string $value, string $operator = '='): self {
        $this->all_conditions[] = [
            "type" => "AND",
            "condition" => $this->prepareCondition($field, $value, $operator),
        ];
        return $this;
    }

    public function orWhere(string $field, string $value, string $operator = '='): self {
        $this->all_conditions[] = [
            "type" => "OR",
            "condition" => $this->prepareCondition($field, $value, $operator),
        ];
        return $this;
    }

    public function join(string $table, string $firstField, string $secondField, string $joinType = 'INNER'): self {
        $joinType = strtoupper($joinType);
        $validTypes = ['INNER', 'LEFT', 'RIGHT', 'FULL', 'CROSS'];

        if (!in_array($joinType, $validTypes))  throw new Exception("JOIN inválido: $joinType");
        # if (!in_array($joinType, $validTypes)) return ['status' => 'error', 'message' => 'JOIN inválido {$joinType}'];

        $this->joins[] = [
            'type' => $joinType,
            'table' => trim($table),
            'condition' => trim($firstField) . ' = ' . trim($secondField),
        ];

        return $this;
    }

    protected function prepareCondition(string $field, string $value, string $operator): string
    {
        $paramKey = ':param_' . count($this->params);
        $this->params[$paramKey] = $value;
        return "{$field} {$operator} {$paramKey}";
    }

    public function select(string $config_search = 'fetchAll', int $config_param = PDO::FETCH_ASSOC): array {
        if (empty($this->table)) return ['status' => 'error', 'message' => 'Tabela não definida'];

        $join_clause = '';
        foreach ($this->joins as $join) {
            $join_clause .= " {$join['type']} JOIN {$join['table']} ON {$join['condition']}";
        }

        $where_clause = '';
        foreach ($this->all_conditions as $index => $condition) {
            $prefix = $index === 0 ? 'WHERE' : $condition['type'];
            $where_clause .= " {$prefix} {$condition['condition']}";
        }

        $sql = "SELECT {$this->columns} FROM {$this->table}{$join_clause}{$where_clause}";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            /** @var array<int, array<string, mixed>> $result */
            $result = (array) $stmt->$config_search($config_param);
            return ['status' => 'success', 'result' => $result];
        } catch (PDOException $error) {
            if (($_ENV['APP_ENV'] == 'development') AND ($_ENV['APP_DEBUG'] == 'True')) return ['status' => 'error', 'message' => $error->getMessage()];
            return ['status' => 'error', 'message' => 'Failed to fetch data:'];
        } finally {
            $this->reset();
        }
    }

    public function insert(array $register_data): array {
        /** @var array<string, string> $register_data */
        $columns = implode(', ', array_keys($register_data));
        $values = array_map(function ($value) {
            return "'" . addslashes($value) . "'";
        }, array_values($register_data));

        $values = implode(', ', $values);
        $sql = "INSERT INTO {$this->table} ({$columns}, created_at) VALUES ({$values}, now()) RETURNING id;";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'result' => $result];
        } catch (PDOException $error) {
            if ($_ENV['APP_ENV'] === 'development' && $_ENV['APP_DEBUG'] === 'True') {
                return ['status' => 'error', 'message' => $error->getMessage()];
            }
            return ['status' => 'error', 'message' => 'Failed to insert data.'];
        } finally {
            $this->reset();
        }
    }
        protected function reset(): void {
        $this->table = '';
        $this->columns = '*';
        $this->all_conditions = [];
        $this->joins = [];
        $this->params = [];
    }
}
