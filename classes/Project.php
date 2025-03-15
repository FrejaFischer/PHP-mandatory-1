<?php

require_once 'Database.php';
require_once 'Logger.php';

Class Project extends Database
{
    /**
     * It gets all projects from the db
     * @return array of projects
     *          Or false if error
     */
    function getAll(): array|false
    {
        return $this->executeSelect("SELECT nProjectID AS project_ID, cName AS name FROM project ORDER BY cName");
    }
    
    /**
     * It gets one departments from an ID from the db
     * @param int department ID to select
     * @return array of departments
     *          Or false if error
     */
    function getByID(int $projectID): array|false
    {
        return $this->executeSelect("SELECT cName AS project_name FROM project WHERE nProjectID = ?", [$projectID]);
    }

    /**
     * It retrieves projects from the db based
     * on a text seach on the name
     * @param string $searchText - The text to search for
     * @return array of projects
     *         Or false is there was an error
     */
    function search(string $searchText): array|false
    {
        return $this->executeSelect("SELECT nProjectID AS project_ID, cName AS name FROM project WHERE cName LIKE ? ORDER BY cName", ["%$searchText%"]);
    }

    /**
     * Validates form from insert project
     * @param array $project The department to insert
     * @return array with error messages inside if error
     *          or empty if there is no errors
     */
    function validate(array $project): array
    {

        $name = trim($project['name']) ?? '';
        $validationErrors = [];
        
        if($name === '') {
            $validationErrors[] = 'Project name is mandatory';
        }
        if(strlen($name) > 128) {
            $validationErrors[] = 'Project name is too long - Maks 128 characters';
        }
        
    
        return $validationErrors;
    }


    /**
     * Inserts project into the db
     * @param array $project - The project to insert
     * @return boolean - true if success, false if error
     */
    function insert(array $project): bool
    {
        $name = htmlspecialchars(trim($project['name']));

        return $this->executeQuery("INSERT INTO project (cName) VALUES (?)", [$name]);
    }

    // /**
    //  * It updates a department in the db
    //  * @param $department The department to update
    //  * @param $departmentID The department id
    //  * @return boolean, true if success, false if error
    //  */
    // function update(array $department, int $departmentID): bool
    // {
    //     $name = htmlspecialchars(trim($department['name']));

    //     return $this->executeQuery("UPDATE `department` SET `cName`=(?) WHERE nDepartmentID = (?)", [$name, $departmentID]);    
    // }
    
    // /**
    //  * It deletes a department in the db
    //  * @param $departmentID The department to delete id
    //  * @return boolean, true if success, false if error
    //  */
    // function delete(int $departmentID): bool
    // {
    //     return $this->executeQuery("DELETE FROM `department` WHERE nDepartmentID = (?)", [$departmentID]);    
    // }
}