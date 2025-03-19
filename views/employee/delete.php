<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Employee.php';

$employeeID = (int) ($_GET['id'] ?? 0); // Make it a number

// relocate if no id is given
if($employeeID === 0){
    header('Location: index.php');
    exit;
}

$employee = new Employee();
$employeeToDelete = $employee->getByID($employeeID);

if(!$employeeToDelete) {
    $errorMessage = 'Employee not found';
} else {
    $employeeToDelete = $employeeToDelete[0];
}

$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){

    if(!isset($_POST['checkConfirm'])) {
        $errorMessage = 'Please confirm the deletion';
        Logger::logText('unsuccessfull in deleting employee. Reason: Tried to without checkbox');
    } else {
        
        if($employee->delete($employeeID)) {
            header('Location:' . BASE_URL);
        } else {
            $errorMessage ='Could not delete employee';
        }
    }
}

$pageTitle = 'Delete Employee';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
?>
<main>
    <a href="view.php?id=<?=$employeeID?>">Back</a>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage?></p>
        </section>
    <?php else: ?>
        <p><strong>First name: </strong><?=$employeeToDelete['first_name'] ?></p>
        <p><strong>Last name: </strong><?=$employeeToDelete['last_name'] ?></p>
        <p><strong>Email: </strong><?=$employeeToDelete['email'] ?></p>
        <p><strong>Birth date: </strong><?=$employeeToDelete['birth_date'] ?></p>
        <p><strong>Department: </strong><?=$employeeToDelete['department_name'] ?></p>
        <form action="delete.php?id=<?=$employeeID?>" method="POST">
            <div>
                <label for="confirm_dlt">Confirm you want to delete this employee</label>
                <input type="checkbox" id="confirm_dlt" name="checkConfirm" value="confirmed" required/>
            </div>
            <button type="submit">Delete permenently</button>
        </form>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>