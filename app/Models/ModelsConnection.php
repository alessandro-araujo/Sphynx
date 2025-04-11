<?php
namespace App\Models;
date_default_timezone_set($_ENV['APP_TIMEZONE']);

use DateTime;
use DateTimeZone;
use PDO;
use PDOException;

class ModelsConnection
{
    protected PDO $pdo;
    private string $dataSource;
    private string $usuario;
    private string $senha;

    public function __construct()
    {
        $this->dataSource = 'mysql:host=' . $_ENV['DB_SERVER'] . ';port=' . $_ENV['DB_PORT'] . ';dbname=' . $_ENV['DB_DATABASE'] . ';charset=utf8mb4';
        $this->usuario = $_ENV['DB_USER'];
        $this->senha = $_ENV['DB_PASSWORD'];
        $this->connection();
    }

    private function connection(): void
    {
        try {
            $this->pdo = new PDO($this->dataSource, $this->usuario, $this->senha);
            $this->pdo->setAttribute(PDO::ATTR_TIMEOUT, 300);
        } catch (PDOException $error) {
            if ($error->getCode() == 2006) {
                // Tenta novamente em caso de perda de conexão
                try {
                    $this->pdo = new PDO($this->dataSource, $this->usuario, $this->senha);
                } catch (PDOException $retryError) {
                    $this->handleError($retryError);
                }
            } else {
                writeLog("Erro com a conexão: " . $error);
                $this->handleError($error);
            }
        }
    }

    private function handleError(PDOException $error): void
    {
        // Tratamento de erro genérico
        echo "<span>ErrorLog PDOException: " . $error->getMessage() . "</span>";
    }

    public function postFavorites(int $id)
    {
        $datetime = new DateTime('now', new DateTimeZone($_ENV['APP_TIMEZONE']));
        $formattedDate = $datetime->format('Y-m-d H:i:s');
        try {
            $postFavorites = $this->pdo->prepare('INSERT INTO favorites (id, date_created) VALUES (:id, :date_created);');
            $postFavorites->bindParam(':id', $id, PDO::PARAM_INT);            
            $postFavorites->bindParam(':date_created', $formattedDate);       
            return $postFavorites->execute();

        } catch (PDOException $Error) {
            writeLog("Erro ao cadastrar favoritos (postFavorites): " . $Error->getMessage());
            return array($Error->getCode(), "message" => $Error->getMessage());
        } finally {
            $postFavorites = null;
        }
    }

    public function getAllFavorites(): array
    {
        try {
            $getAllFavorites = $this->pdo->prepare("SELECT id FROM favorites");
            $getAllFavorites->execute();
            $resultgetAllFavorites = $getAllFavorites->fetchAll(PDO::FETCH_ASSOC);            
             return $resultgetAllFavorites ?: [];

        } catch (PDOException $Error) {
            writeLog("Erro ao buscar favoritos: " . $Error->getMessage());
            return ['error' => true, 'message' => 'Falha ao buscar favoritos.'];
        } finally {
            $getAllFavorites = null;
        }
    }

    public function delFavorites(int $id)
    {
        try {
            $delFavorites = $this->pdo->prepare("DELETE FROM favorites WHERE id = :id");
            $delFavorites->bindParam(':id', $id, PDO::PARAM_INT);
            $delFavorites->execute();
            return $delFavorites->rowCount() > 0;
        } catch (PDOException $Error) {
            writeLog("Erro ao deletar favoritos: " . $Error->getMessage());
            return false;
        } finally {
            $delFavorites = null;
        }
    }
    
    public function getAllLogs(int $currentPage = 1): array
    {
        if ($currentPage != 0){
            try {
                $ITEMS_PER_PAGE = 10;
                $allInsertQuery = $this->pdo->prepare("SELECT COUNT(*) as total FROM logs_endpoints");
                $allInsertQuery->execute();
                $allInserts = $allInsertQuery->fetch(PDO::FETCH_ASSOC)['total'];
                $allPages = ceil($allInserts / $ITEMS_PER_PAGE);
                $offset = ($currentPage - 1) * $ITEMS_PER_PAGE;
                
                $getAllLogs = $this->pdo->prepare("SELECT * FROM logs_endpoints ORDER BY date_created DESC LIMIT :offsets, :forPage");
                # $getAllLogs = $this->pdo->prepare("SELECT * FROM logs_endpoints LIMIT :offsets, :forPage");
                $getAllLogs->bindParam(':offsets', $offset, PDO::PARAM_INT);
                $getAllLogs->bindParam(':forPage', $ITEMS_PER_PAGE, PDO::PARAM_INT);
                $getAllLogs->execute();
                $resultgetAllLogs = $getAllLogs->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($resultgetAllLogs)) {
                    return ["clients" => $resultgetAllLogs, "totalPages" => $allPages];
                } else {
                    return array();
                }
            } catch (PDOException $Error) {
                writeLog("Erro ao buscar Logs: " . $Error->getMessage());
                return array("message" => $Error->getMessage());
            }finally {
                $getAllLogs = null;
            }
        }else{
            return array();
        }
    }

    public function postLogs(string $method, string $route, string $ip, $user_agent)
    {
        $datetime = new DateTime('now', new DateTimeZone($_ENV['APP_TIMEZONE']));
        $formattedDate = $datetime->format('Y-m-d H:i:s');
        try {
            $postLogs = $this->pdo->prepare('INSERT INTO logs_endpoints (method, route, ip, user_agent, date_created) VALUES 
                                                            (:method, :route, :ip, :user_agent, :date_created);');
            $postLogs->bindParam(':method', $method);            
            $postLogs->bindParam(':route', $route);   
            $postLogs->bindParam(':ip', $ip);   
            $postLogs->bindParam(':user_agent', $user_agent);   
            $postLogs->bindParam(':date_created', $formattedDate);       
            return $postLogs->execute();

        } catch (PDOException $Error) {
            writeLog("Erro ao cadastrar logs (postLogs): " . $Error->getMessage());
            return array($Error->getCode(), "message" => $Error->getMessage());
        } finally {
            $postLogs = null;
        }
    }

    public function postComment(string $name, string $id_film, string $comment)
    {
        $datetime = new DateTime('now', new DateTimeZone($_ENV['APP_TIMEZONE']));
        $formattedDate = $datetime->format('Y-m-d H:i:s');
        try {
            $postComment = $this->pdo->prepare('INSERT INTO comments (name, id_film, comment, date_created) VALUES 
                                                            (:name, :id_film, :comment, :date_created);');
            $postComment->bindParam(':name', $name);            
            $postComment->bindParam(':id_film', $id_film);   
            $postComment->bindParam(':comment', $comment);   
            $postComment->bindParam(':date_created', $formattedDate);       
            return $postComment->execute();

        } catch (PDOException $Error) {
            writeLog("Erro ao cadastrar Comentarios (postComment): " . $Error->getMessage());
            return array($Error->getCode(), "message" => $Error->getMessage());
        } finally {
            $postComment = null;
        }
    }
    public function getAllComments($id): array
    {
        try {
            $getgetAllComments = $this->pdo->prepare("SELECT * FROM comments WHERE id_film = :id_film ORDER BY date_created DESC");
            $getgetAllComments->bindParam(':id_film', $id); 
            $getgetAllComments->execute();
            $resultgetgetAllComments = $getgetAllComments->fetchAll(PDO::FETCH_ASSOC);            
             return $resultgetgetAllComments ?: [];

        } catch (PDOException $Error) {
            writeLog("Erro ao buscar favoritos: " . $Error->getMessage());
            return ['error' => true, 'message' => 'Falha ao buscar favoritos.'];
        } finally {
            $getgetAllComments = null;
        }
    }
}
