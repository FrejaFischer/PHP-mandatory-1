<?php

require_once 'Database.php';
require_once 'Logger.php';

Class Department extends Database
{
    /**
     * It gets all departments from the db
     * @param $pdo A PDO database connnection
     * @return An array of departments
     *          Or false if error
     */
    function getAll(): array|false
    {
        // $pdo = $this->connect();
        
        $sql =<<<SQL
            SELECT * FROM department
            ORDER BY cName
        SQL;
    
        try{
            $stmt = $this->pdo->prepare($sql);
            // $stmt = $pdo->prepare($sql);
            $stmt->execute();
    
            return $stmt->fetchAll();
        } catch(PDOException $e){
            Logger::logText('Error getting all departments', $e);
            return false;
        }
    }
    
    /**
     * It gets one departments from an ID from the db
     * @param $pdo A PDO database connnection
     * @param An ID of a department
     * @return An array of departments
     *          Or false if error
     */
    function getByID(int $departmentID): array|false
    {
        // $pdo = $this->connect();
        $sql =<<<SQL
                SELECT cName
                FROM department
                WHERE nDepartmentID = :departmentID;
            SQL;
            try {
                $stmt = $this->pdo->prepare($sql);
                // $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':departmentID', $departmentID);
                $stmt->execute();
    
                if ($stmt->rowCount() === 1) {
                    return $stmt->fetch();
                }
                return false;
            } catch (PDOException $e) {
                Logger::logText('Error getting department by ID', $e);
                return false;
            }
    }
}