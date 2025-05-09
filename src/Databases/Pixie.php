<?php

namespace Database;

use Pixie\Connection;

class Pixie
{
    protected $db;

    public function __construct()
    {
        error_reporting(E_ALL & ~E_DEPRECATED);
        $config = [
            'driver'    => $_ENV['DB_DRIVER'],
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_DATABASE'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => $_ENV['DB_CHARSET'],
            'collation' => $_ENV['DB_COLLATION'],
            'prefix'    => $_ENV['DB_PREFIX'] ?? ''
        ];

        try {
            $connection = new Connection($config['driver'], $config, 'QB');
            $this->db = $connection->getQueryBuilder();
        } catch (\Exception $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }
}
