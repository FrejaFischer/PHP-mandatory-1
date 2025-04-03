<?php

require_once 'DBCredentials.php';

Class Database extends DBCredentials
{
    protected ?PDO $pdo;

    public function __construct()
    {
        parent::__construct(); // Call parent constructor to load env variables
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        
        $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

     /**
     * Execute SELECT SQL query in the db
     * @param string $sql SQL query
     * @param array $params Query parameter
     * @return array of selected data
     *          Or false if error
     */
     protected function executeSelect(string $sql, array $params = []): array|false {
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        } catch(PDOException $e){
            Logger::logText('Error getting data', $e);
            return false;
        }
    }
    
    /**
     * Execute INSERT, UPDATE or DELETE SQL query in the db
     * @param string $sql SQL query
     * @param array $params Query parameter
     * @return bool true if succes
     *          Or false if error
     */
     protected function executeQuery(string $sql, array $params = []): bool {
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);

            return $stmt->rowCount() === 1;
        } catch(PDOException $e){
            Logger::logText('Error getting data', $e);
            return false;
        }
    }
}