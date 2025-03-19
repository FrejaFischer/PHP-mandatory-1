<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Department.php';
require_once ROOT_PATH . '/classes/Employee.php';

$departmentID = (int) ($_GET['id'] ?? 0); // Make it a number

// relocate if no id is given
if($departmentID === 0){
    header('Location: index.php');
    exit;
}

// Check if department is empty
$employee = new Employee();
$employeesInDepartment = $employee->getAllByDepartment($departmentID);

// Set Error if not empty
if(count($employeesInDepartment) > 0) {
    $errorMessage = 'The department is not empty';
}

// Find department to delete
$department = new Department();
$departmentToDelete = $department->getByID($departmentID);

if(!$departmentToDelete) {
    $errorMessage = 'Department not found';
} else {
    $departmentToDelete = $departmentToDelete[0];
}

// Handle delete request
$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){

    if(!isset($_POST['checkConfirm'])) {
        $errorMessage = 'Please confirm the deletion';
        Logger::logText('unsuccessfull in deleting department. Reason: Tried to without checkbox');
    } else {
        if($department->delete($departmentID)) {
            header('Location:' . BASE_URL);
        } else {
            $errorMessage ='Could not delete department';
        }
    }
}

$pageTitle = 'Delete Department';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
?>
<main>
    <a href="view.php?id=<?=$departmentID?>">Back</a>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage?></p>
        </section>
    <?php else: ?>
        <p><strong>Name: </strong><?=$departmentToDelete['department_name'] ?></p>
        <form action="delete.php?id=<?=$departmentID?>" method="POST">
            <div>
                <label for="confirm_dlt">Confirm you want to delete this department</label>
                <input type="checkbox" id="confirm_dlt" name="checkConfirm" value="confirmed" required/>
            </div>
            <button type="submit">Delete permenently</button>
        </form>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>