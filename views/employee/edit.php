<?php
require_once '../../initialise.php';

$employeeID = (int) ($_GET['id'] ?? 0); // Make it a number

if($employeeID === 0){
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Employee.php';
require_once ROOT_PATH . '/classes/Department.php';

$firstName = '';
$lastName = '';
$email = '';
$birth = '';
$chosenDepartment = '';

$department = new Department();
$allDepartments = $department->getAll();

if(!$allDepartments){
    $errorMessage = 'There was an error, while retrieving the departments';
}

$employee = new Employee();
$employeeToEdit = $employee->getByID($employeeID);

if(!$employeeToEdit) {
    $errorMessage = 'Employee not found';
} else {
    $employeeToEdit = $employeeToEdit[0];
    
    $validationFailure = false;
    $firstName = $employeeToEdit['first_name'] ?? '';
    $lastName = $employeeToEdit['last_name'] ?? '';
    $email = $employeeToEdit['email'] ?? '';
    $birth = $employeeToEdit['birth_date'] ?? '';
    $chosenDepartment = $employeeToEdit['department_id'] ?? '';
    
}


$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){

    // Input data to ensure inputs value stays if error
    $firstName = htmlspecialchars($_POST['firstName']) ?? '';
    $lastName = htmlspecialchars($_POST['lastName']) ?? '';
    $email = htmlspecialchars($_POST['email']) ?? '';
    $birth = htmlspecialchars($_POST['birth']) ?? '';
    $chosenDepartment = htmlspecialchars($_POST['department']) ?? '';

    $validationErrors = $employee->validate($_POST);
    
    if(!empty($validationErrors)) {
        $validationFailure = true;
        $errorMessage = join(', ', $validationErrors);
    } else {
        // update employee
        if (!$employee->update($_POST, $employeeID)) {
            $errorMessage = 'It was not possible to update the employee. Make sure there is an update added, or come back later';
        } else {
            // if successfull
            header("Location: view.php?id=$employeeID");
        }
    }
}



$pageTitle = 'Edit employee';
include_once ROOT_PATH . '/public/header.php';
?>
<nav>
    <ul>
        <li><a href=<?= BASE_URL . "/views/employee/view.php?id=$employeeID"?>>Back</a></li>
    </ul>
</nav>
<main>
<?php if(isset($errorMessage)):?>
            <section>
                <p class="error"><?=$errorMessage?></p>
            </section>
    <?php endif; ?>

    <?php if($postRequest && empty($errorMessage)):?>
                <section>
                    <p>Employee updated succesfully!</p>
                </section>
    <?php endif; ?>

    <form action="edit.php?id=<?=$employeeID?>" method="POST">
        <div>
            <label for="txtFirstName">First Name</label>
            <input type="text" id="txtFirstName" name="firstName" value="<?= $firstName ?>">
        </div>
        <div>
            <label for="txtLastName">Last Name</label>
            <input type="text" id="txtLastName" name="lastName" value="<?= $lastName ?>">
        </div>
        <div>
            <label for="txtEmail">Email</label>
            <input type="text" id="txtEmail" name="email" value="<?= $email ?>">
        </div>
        <div>
            <label for="dBirth">Birthday</label>
            <input type="date" id="dBirth" name="birth" value="<?= $birth ?>">
        </div>
        <div>
            <label for="department">Department</label>
            <select name="department" id="department">
                <?php foreach($allDepartments as $dept):?>
                    <option value="<?=$dept['nDepartmentID']?>"  <?= ($dept['nDepartmentID'] == $chosenDepartment) ? 'selected' : '' ?> > <?=$dept['cName']?></option>
                <?php endforeach?>
            </select>
        </div>
        <div>
            <button type="submit">Update employee</button>
        </div>
    </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>