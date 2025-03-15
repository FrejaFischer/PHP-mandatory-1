<?php

require_once 'Database.php';
require_once 'Logger.php';

Class Department extends Database
{
    /**
     * It gets all departments from the db
     * @return array of departments
     *          Or false if error
     */
    function getAll(): array|false
    {
        return $this->executeSelect("SELECT nDepartmentID AS department_ID, cName AS name FROM department ORDER BY cName");
    }
    
    /**
     * It gets one departments from an ID from the db
     * @param int department ID to select
     * @return array of departments
     *          Or false if error
     */
    function getByID(int $departmentID): array|false
    {
        return $this->executeSelect("SELECT cName AS department_name FROM department WHERE nDepartmentID = ?", [$departmentID]);
    }

    /**
     * It retrieves departments from the db based
     * on a text seach on the name
     * @param string $searchText - The text to search for
     * @return array of departments
     *         Or false is there was an error
     */
    function search(string $searchText): array|false
    {
        return $this->executeSelect("SELECT nDepartmentID AS department_ID, cName AS name FROM department WHERE cName LIKE ? ORDER BY cName", ["%$searchText%"]);
    }

    /**
     * Validates form from insert department
     * @param array $department The department to insert
     * @return array with error messages inside if error
     *          or empty if there is no errors
     */
    function validate(array $department): array
    {

        $name = trim($department['name']) ?? '';
        $validationErrors = [];
        
        if($name === '') {
            $validationErrors[] = 'Department name is mandatory';
        }
        if(strlen($name) > 64) {
            $validationErrors[] = 'Department name is too long - Maks 64 characters';
        }
        
    
        return $validationErrors;
    }


    /**
     * Inserts department into the db
     * @param array $department - The department to insert
     * @return boolean - true if success, false if error
     */
    function insert(array $department): bool
    {
        $name = htmlspecialchars(trim($department['name']));

        return $this->executeQuery("INSERT INTO department (cName) VALUES (?)", [$name]);
    }

    /**
     * It updates a department in the db
     * @param $department The department to update
     * @param $departmentID The department id
     * @return boolean, true if success, false if error
     */
    function update(array $department, int $departmentID): bool
    {
        $name = htmlspecialchars(trim($department['name']));

        return $this->executeQuery("UPDATE `department` SET `cName`=(?) WHERE nDepartmentID = (?)", [$name, $departmentID]);    
    }
    
    /**
     * It deletes a department in the db
     * @param $departmentID The department to delete id
     * @return boolean, true if success, false if error
     */
    function delete(int $departmentID): bool
    {
        return $this->executeQuery("DELETE FROM `department` WHERE nDepartmentID = (?)", [$departmentID]);    
    }
}