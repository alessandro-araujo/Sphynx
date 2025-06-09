<?php

namespace Database;

use App\Interfaces\Database;
use App\Helpers\ResponseHttp;
use PDO;
use PDOException;
use Exception;

class InlineSQL implements Database {
    protected string $table = '';
    protected string $where_clause = '';
    protected string $columns = '*';
    /** @var array<int, array{type: string, table: string, condition: string}> $joins */
    protected array $joins = [];
    /** @var array<int, array{type: string, condition: string}> $all_conditions */
    protected array $all_conditions = [];
    /** @var array<string> $params */
    protected array $params = [];

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

        // $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        // $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        // $options = [
        //    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        //    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        //    PDO::ATTR_TIMEOUT            => 10,
        // ];

        $dataSource = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        // dd($dataSource);
        try {
            $this->pdo = new PDO($dataSource, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_TIMEOUT, 300);

            // new PDO($dsn, $user, $password, $options);
            // $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            // $this->pdo->setAttribute(PDO::ATTR_CURSOR, PDO::CURSOR_SCROLL);
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

    protected function prepareCondition(string $field, string $value, string $operator): string {
        $paramKey = ':param_' . count($this->params);
        $this->params[$paramKey] = $value;
        return "{$field} {$operator} {$paramKey}";
    }

    public function delete(): array {
        $where_clause = '';
        foreach ($this->all_conditions as $index => $condition) {
            $prefix = $index === 0 ? 'WHERE' : $condition['type'];
            $where_clause .= " {$prefix} {$condition['condition']}";
        }

        $sql = "DELETE FROM {$this->table} {$where_clause}";
        if ($_ENV['SQL_DEBUG'] === 'True') {
            ob_start();
            print_r($sql); print('');
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            return ['status' => 'success', 'result' => 'OK'];
        } catch (PDOException $error) {
            if (($_ENV['APP_ENV'] == 'development') AND ($_ENV['APP_DEBUG'] == 'True')) return ['status' => 'error', 'message' => $error->getMessage()];
            return ['status' => 'error', 'message' => 'Failed to fetch data:'];
        } finally {
            $this->reset();
        }


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
        if ($_ENV['SQL_DEBUG'] === 'True') {
            ob_start();
            print_r($sql); print('');
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            /** @var array<int, array<string, mixed>> $result */
            $result = (array) $stmt->$config_search($config_param);

            if(isset($result[0]) AND $result[0] === false) {
                return ['status' => 'error', 'result' => false];
            }

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

        $now = date('Y-m-d H:i:s');

        $values = implode(', ', $values);
        # RETURNING id postgre
        # $lastId = $pdo->lastInsertId(); mysql
        $sql = "INSERT INTO {$this->table} ({$columns}, created_at) VALUES ({$values}, '{$now}');";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'result' => $result];
        } catch (PDOException $error) {
            if ($_ENV['APP_ENV'] === 'development' && $_ENV['APP_DEBUG'] === 'True') {
                return ['status' => 'error', 'message' => $error->getMessage()];
            }
            return ['status' => 'error', 'message' => 'insert_data'];
        } finally {
            $this->reset();
        }
    }

    public function update(array $update_data): array {
        if (empty($this->table)) {
            return ['status' => 'error', 'message' => 'Table not defined'];
        }
        $set_parts = [];
        foreach ($update_data as $column => $value) {
            $set_parts[] = "$column = :set_$column";
        }
        $set_clause = implode(', ', $set_parts);

        foreach ($this->all_conditions as $index => $condition) {
            $prefix = $index === 0 ? 'WHERE' : $condition['type'];
            $this->where_clause .= " {$prefix} {$condition['condition']}";
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET {$set_clause} {$this->where_clause};");
            foreach ($update_data as $column => $value) {
                $stmt->bindValue(":set_$column", $value);
            }
            foreach ($this->params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            $result = $stmt->rowCount();

            if ($result > 0) {
                $update_at = "updated_at = :set_updated_at";
                $stmt_update_at = $this->pdo->prepare("UPDATE {$this->table} SET {$update_at} {$this->where_clause};");
                $stmt_update_at->bindValue('set_updated_at', date('Y-m-d H:i:s'));
                foreach ($this->params as $key => $value) {
                    $stmt_update_at->bindValue($key, $value);
                }
                $stmt_update_at->execute();
            }

            return ['status' => 'success', 'result' => $stmt->rowCount()];
        } catch (PDOException $error) {
            if ($_ENV['APP_ENV'] === 'development' && $_ENV['APP_DEBUG'] === 'True') {
                return ['status' => 'error', 'message' => $error->getMessage()];
            }
            return ['status' => 'error', 'message' => 'Error updating data. '];
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
        $this->where_clause = '';
    }
}
