<?php
require_once ROOT_PATH . '/classes/Database.php';
require_once ROOT_PATH . '/classes/Department.php';
require_once ROOT_PATH . '/classes/Logger.php';

Class Employee extends Database
{
    /**
     * It retrieves all employees from the db
     * @param $pdo A PDO database connnection
     * @return array associative array
     *         Or false is there was an error
     */
    function getAll(): array|false
    {
        // $pdo = $this->connect();
        $sql =<<<SQL
            SELECT employee.nEmployeeID, employee.cFirstName, employee.cLastName, employee.dBirth, department.cName AS department_name
            FROM employee
            INNER JOIN department ON department.nDepartmentID = employee.nDepartmentID
            ORDER BY cFirstName, cLastName;
        SQL;
        try{
            $stmt = $this->pdo->prepare($sql);
            // $stmt = $pdo->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e){
            //echo "Database Connection Failed: " . $e->getMessage();
            Logger::logText('Error getting all employee', $e);
            return false;
        } catch(Exception $e){
            echo "An error occurred: " . $e->getMessage();
            return [];
        } catch(Error $e){
            echo "A serious system error occurred: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * It retrieves employee from the db based
     * on a text seach on the first and last name
     * @param $pdo A PDO database connnection
     * @param $searchText The text to search for
     * @return array if succes
     *         Or false is there was an error
     */
    function search(string $searchText): array|false
    {
        // $pdo = $this->connect();
        $sql =<<<SQL
            SELECT nEmployeeID, cFirstName, cLastName, dBirth
            FROM employee
            WHERE cFirstName LIKE ?
            OR cLastName LIKE ?
            ORDER BY cFirstName, cLastName;
        SQL;
        
        try{
            $stmt = $this->pdo->prepare($sql);
            // $stmt = $pdo->prepare($sql);
            $stmt->execute(["%$searchText%", "%$searchText%"]);
            
            return $stmt->fetchAll();
        } catch(PDOException $e){
            Logger::logText('Error getting all employee', $e);
            return false;
        } catch(Exception $e){
            echo "An error occurred: " . $e->getMessage();
            return [];
        } catch(Error $e){
            echo "A serious system error occurred: " . $e->getMessage();
            return [];
        }
    }
    
    /**
     * It retrieves employee from the db based
     * on an ID
     * @param $searchText The text to search for
     * @return An associative array
     *         Or false is there was an error
     */
    function getByID(int $employeeID): array|false
    {
        // $pdo = $this->connect();
        $sql =<<<SQL
            SELECT 
                employee.cFirstName AS first_name, 
                employee.cLastName AS last_name, 
                employee.cEmail AS email, 
                employee.dBirth AS birth_date, 
                employee.nDepartmentID AS department_id, 
                department.cName AS department_name,
                department.nDepartmentID AS department_ID
            FROM employee INNER JOIN department
                ON employee.nDepartmentID = department.nDepartmentID
            WHERE nEmployeeID = :employeeID;
        SQL;
        
        try{
            $stmt = $this->pdo->prepare($sql);
            // $stmt = $pdo->prepare($sql);
            
            $stmt->bindValue(':employeeID', $employeeID);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e){
            Logger::logText('Error getting all employee', $e);
            return false;
        } catch(Exception $e){
            Logger::logText('Error getting all employee', $e);
            echo "An error occurred: " . $e->getMessage();
            return false;
        } catch(Error $e){
            Logger::logText('Error getting all employee', $e);
            echo "A serious system error occurred: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Retrieves employees from the db based
     * on a department ID
     * @param $departmentID The department which employees should be from
     * @return array of employees
     *         Or false is there was an error
     */
    function getAllByDepartment(int $departmentID): array|false
    {
        return $this->executeSelect("SELECT cFirstName AS first_name, cLastName AS last_name, nEmployeeID as employee_ID FROM employee WHERE nDepartmentID = ?", [$departmentID]);
    }
    
    /**
     * Retrieves employees from the db based
     * on a department ID
     * @param $departmentID The department which employees should be from
     * @return array of employees
     *         Or false is there was an error
     */
    function getAllByProject(int $projectID): array|false
    {
        return $this->executeSelect("SELECT employee.cFirstName AS first_name, employee.cLastName AS last_name, employee.nEmployeeID AS employee_ID, department.cName AS department_name FROM employee
                                    INNER JOIN emp_proy ON emp_proy.nEmployeeID  = employee.nEmployeeID
                                    INNER JOIN department ON department.nDepartmentID = employee.nDepartmentID
                                    WHERE emp_proy.nProjectID = ?", [$projectID]);
    }
    
    /**
     * It inserts an employee into the db
     * @param $pdo A PDO database connnection
     * @param $employee The employee to insert
     * @return boolean, true if success, false if error
     */
    function insert(array $employee): bool
    {
        // $pdo = $this->connect();
        $sql =<<<SQL
            INSERT INTO `employee`(`cFirstName`, `cLastName`, `cEmail`, `dBirth`, `nDepartmentID`) 
            VALUES (?, ?, ?, ?, ?);
        SQL;
        
        try{
            $firstName = htmlspecialchars(trim($employee['firstName']));
            $lastName = htmlspecialchars(trim($employee['lastName']));
            $email = htmlspecialchars(trim($employee['email']));
            $birth = htmlspecialchars(trim($employee['birth']));
            $departmentID = htmlspecialchars(trim($employee['department']));
            
            $stmt = $this->pdo->prepare($sql);
            // $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $firstName, 
                $lastName, 
                $email, 
                $birth, 
                $departmentID
            ]);
            
            return $stmt->rowCount() === 1;
            
            
        } catch(PDOException $e){
            Logger::logText('Error inserting employee', $e, 'Function:'.__FUNCTION__ ,'Line number:'.__LINE__);
            return false;
        }
    }
    
    /**
     * Validates form from insert employee
     * @param $employee The employee to insert, taken from $_POST
     * @return array, with error messages inside if error
     *          or empty if there is no errors
     */
    function validate(array $employee): array
    {

        $firstName = trim($employee['firstName']) ?? '';
        $lastName = trim($employee['lastName']) ?? '';
        $email =trim($employee['email']) ?? '';
        $birth =trim($employee['birth']) ?? '';
        $departmentID =trim($employee['department']) ?? '';
        
        
        $validationErrors = [];
    
        if($birth === ''){
            $validationErrors[] = 'Birthday is mandatory';
        } else {
            $today = new DateTime(); // todays date
            $birthDay = DateTime::createFromFormat('Y-m-d', $birth); // convert date from string to a DateTimeInterface with the format being Y-m-d
            $age = $today->diff($birthDay)->y; // finds the age of user in years (y)
            
            // check if age is too young
            if($age < 16) {
                $validationErrors[] = 'Employee must be at least 16 years old';
            }
        }
    
    
        if($firstName === '') {
            $validationErrors[] = 'First name is mandatory';
        }
        if($lastName === '') {
            $validationErrors[] = 'Last name is mandatory';
        }
    
        if($email === '') {
            $validationErrors[] = 'Email is mandatory';
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $validationErrors[] = 'Invalid email';
        }
    
        // to do: validate if department exist
        if($departmentID === ''){
            $validationErrors[] = 'Department is mandatory';
        } else {
            $department = new Department();
            if(!$department->getByID($departmentID)) {
            $validationErrors[] = 'The department does not exist.';
            }
        }
    
        return $validationErrors;
    }
    
    /**
     * It updates an employee in the db
     * @param $pdo A PDO database connnection
     * @param $employee The employee to update
     * @param $employeeID The employees id
     * @return boolean, true if success, false if error
     */
    function update(array $employee, int $employeeID): bool
    {
        // $pdo = $this->connect();
        $sql =<<<SQL
            UPDATE `employee` SET `cFirstName` = :firstName, `cLastName` = :lastName, `cEmail` = :email, `dBirth` = :birthDate, `nDepartmentID` = :departmentID
            WHERE nEmployeeID = :employeeID
        SQL;
        try{
            $firstName = htmlspecialchars(trim($employee['firstName']));
            $lastName = htmlspecialchars(trim($employee['lastName']));
            $email = htmlspecialchars(trim($employee['email']));
            $birth = htmlspecialchars(trim($employee['birth']));
            $departmentID = htmlspecialchars(trim($employee['department']));
            
            $stmt = $this->pdo->prepare($sql);
            // $stmt = $pdo->prepare($sql);
            
            $stmt->bindValue(':firstName', $firstName);
            $stmt->bindValue(':lastName', $lastName);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':birthDate', $birth);
            $stmt->bindValue(':departmentID', $departmentID);
            $stmt->bindValue(':employeeID', $employeeID);
            $stmt->execute();
            
            return $stmt->rowCount() === 1;
            
            
        } catch(PDOException $e){
            Logger::logText('Error updating employee', $e);
            return false;
        }
    }
    
    /**
     * It deletes an employee in the db
     * @param $pdo A PDO database connnection
     * @param $employeeID The employees id
     * @return boolean, true if success, false if error
     */
    function delete(int $employeeID): bool
    {
        // $pdo = $this->connect();
        $sql =<<<SQL
            DELETE FROM `employee` WHERE nEmployeeID = :employeeID
        SQL;
    
        try{
            $stmt = $this->pdo->prepare($sql);
            // $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':employeeID', $employeeID);
            $stmt->execute();
            
            return $stmt->rowCount() === 1;
            
            
        } catch(PDOException $e){
            Logger::logText('Error deleting employee', $e);
            return false;
        }
    }

    // when object gets echo, this is called
    public function __toString(): string
    {
        return 'Hello, this is the employee class';
    }
}