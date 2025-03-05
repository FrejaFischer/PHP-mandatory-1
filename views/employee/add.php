<?php
require_once '../../initialise.php';
require_once ROOT_PATH . '/classes/Employee.php';
require_once ROOT_PATH . '/classes/Department.php';

$validationFailure = false;
$firstName = '';
$lastName = '';
$email = '';
$birth = '';
$chosenDepartment = '';

$department = new Department();
$allDepartments = $department->getAll();

if(!$allDepartments){
    $errorMessage = 'There was an error, while retrieving data';
}

$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){
    $employee = new Employee();

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
        if (!$employee->insert($_POST)) {
            $errorMessage = 'It was not possible to insert the new employee';
        }
    }
}

$pageTitle = 'Add employee';
include_once ROOT_PATH . '/public/header.php';
?>
<nav>
    <ul>
        <li><a href="<?=BASE_URL . '/views/employee'?>">Back</a></li>
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
                    <p>Employee inserted succesfully!</p>
                </section>
    <?php endif; ?>

    <form action="add.php" method="POST">
        <div>
            <label for="txtFirstName">First Name</label>
            <input type="text" id="txtFirstName" name="firstName" value="<?= isset($errorMessage) ? $firstName : '' ?>">
        </div>
        <div>
            <label for="txtLastName">Last Name</label>
            <input type="text" id="txtLastName" name="lastName" value="<?= isset($errorMessage) ? $lastName : '' ?>">
        </div>
        <div>
            <label for="txtEmail">Email</label>
            <input type="text" id="txtEmail" name="email" value="<?= isset($errorMessage) ? $email : '' ?>">
        </div>
        <div>
            <label for="dBirth">Birthday</label>
            <input type="date" id="dBirth" name="birth" value="<?= isset($errorMessage) ? $birth : '' ?>">
        </div>
        <div>
            <label for="department">Department</label>
            <select name="department" id="department">
                <?php foreach($allDepartments as $dep):?>
                    <option value="<?=$dep['nDepartmentID']?>" <?= ($dep['nDepartmentID'] == $chosenDepartment) ? 'selected' : '' ?> ><?=$dep['cName']?></option>
                <?php endforeach?>
            </select>
        </div>
        <div>
            <button type="submit">Add employee</button>
        </div>
    </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>