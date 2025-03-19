<?php
require_once ROOT_PATH . '/classes/Database.php';
require_once ROOT_PATH . '/classes/Department.php';
require_once ROOT_PATH . '/classes/Logger.php';

Class Employee extends Database
{
    /**
     * It retrieves all employees from the db
     * @return array associative array
     *         Or false is there was an error
     */
    function getAll(): array|false
    {
        return $this->executeSelect("SELECT employee.nEmployeeID AS employee_ID, employee.cFirstName AS name, employee.cLastName AS lastName, employee.dBirth AS birth, department.cName AS department_name
            FROM employee
            INNER JOIN department ON department.nDepartmentID = employee.nDepartmentID
            ORDER BY cFirstName, cLastName;");
    }
    
    /**
     * It retrieves employee from the db based
     * on a text seach on the first and last name
     * @param $searchText The text to search for
     * @return array if succes
     *         Or false is there was an error
     */
    function search(string $searchText): array|false
    {
        return $this->executeSelect("SELECT nEmployeeID, cFirstName, cLastName, dBirth FROM employee WHERE cFirstName LIKE ? OR cLastName LIKE ? ORDER BY cFirstName, cLastName;", ["%$searchText%", "%$searchText%"]);
    }
    
    /**
     * It retrieves employee from the db based
     * on an ID
     * @param int $employeeID The employee to search for
     * @return array if succes associative array
     *         Or false is there was an error
     */
    function getByID(int $employeeID): array|false
    {
        return $this->executeSelect("SELECT 
                employee.cFirstName AS first_name, 
                employee.cLastName AS last_name, 
                employee.cEmail AS email, 
                employee.dBirth AS birth_date, 
                employee.nDepartmentID AS department_id, 
                department.cName AS department_name,
                department.nDepartmentID AS department_ID
            FROM employee INNER JOIN department
                ON employee.nDepartmentID = department.nDepartmentID
            WHERE nEmployeeID = ?;", [$employeeID]);
    }

    /**
     * Retrieves all employees from the db based
     * on a department ID
     * @param int departmentID The department which employees should be from
     * @return array of employees
     *         Or false is there was an error
     */
    function getAllByDepartment(int $departmentID): array|false
    {
        return $this->executeSelect("SELECT cFirstName AS first_name, cLastName AS last_name, nEmployeeID as employee_ID FROM employee WHERE nDepartmentID = ?", [$departmentID]);
    }
    
    /**
     * Retrieves employees from the db based
     * on a project ID
     * @param int $projectID The project which employees should be from
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
     * @param array $employee The employee to insert
     * @return boolean, true if success, false if error
     */
    function insert(array $employee): bool
    {
        $firstName = htmlspecialchars(trim($employee['firstName']));
        $lastName = htmlspecialchars(trim($employee['lastName']));
        $email = htmlspecialchars(trim($employee['email']));
        $birth = htmlspecialchars(trim($employee['birth']));
        $departmentID = htmlspecialchars(trim($employee['department']));

        return $this->executeQuery("INSERT INTO `employee`(`cFirstName`, `cLastName`, `cEmail`, `dBirth`, `nDepartmentID`) 
            VALUES (?, ?, ?, ?, ?);",[$firstName, $lastName, $email, $birth, $departmentID]);
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
     * @param array $employee The employee info to update
     * @param int $employeeID The employees id
     * @return boolean, true if success, false if error
     */
    function update(array $employee, int $employeeID): bool
    {
        $firstName = htmlspecialchars(trim($employee['firstName']));
        $lastName = htmlspecialchars(trim($employee['lastName']));
        $email = htmlspecialchars(trim($employee['email']));
        $birth = htmlspecialchars(trim($employee['birth']));
        $departmentID = htmlspecialchars(trim($employee['department']));

        return $this->executeQuery("UPDATE `employee` SET `cFirstName` = ?, `cLastName` = ?, `cEmail` = ?, `dBirth` = ?, `nDepartmentID` = ?
            WHERE nEmployeeID = ?", [$firstName, $lastName, $email, $birth, $departmentID, $employeeID]);
    }
    
    /**
     * It deletes an employee in the db
     * @param $employeeID The employee to delete id
     * @return boolean, true if success, false if error
     */
    function delete(int $employeeID): bool
    {
        return $this->executeQuery("DELETE FROM `employee` WHERE nEmployeeID = ?", [$employeeID]);
    }

    // when object gets echo, this is called
    public function __toString(): string
    {
        return 'Hello, this is the employee class';
    }
}