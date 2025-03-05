<?php
require_once '../../initialise.php';


$employeeID = (int) ($_GET['id'] ?? 0); // Make it a number

if($employeeID === 0){
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Employee.php';

$employee = new Employee();
$employee = $employee->getByID($employeeID);

if(!$employee) {
    $errorMessage = 'There was an error';
} else {
    $employee = $employee[0];
}

$pageTitle = 'Employee';
include_once ROOT_PATH . '/public/header.php';
?>
<main>
    <a href="<?=BASE_URL . '/views/employee'?>">Back</a>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage?></p>
        </section>
    <?php else: ?>
        <p><strong>First name: </strong><?=$employee['first_name'] ?></p>
        <p><strong>Last name: </strong><?=$employee['last_name'] ?></p>
        <p><strong>Email: </strong><?=$employee['email'] ?></p>
        <p><strong>Birth date: </strong><?=$employee['birth_date'] ?></p>
        <p><strong>Department: </strong><a href=<?= BASE_URL . "/views/department/view.php?id={$employee['department_ID']}"?>><?=$employee['department_name'] ?></a></p>
        <p><a href=<?= BASE_URL . "/views/employee/edit.php?id=$employeeID"?>>Edit employee</a></p>
        <p><a href=<?= BASE_URL . "/views/employee/delete.php?id=$employeeID"?>>Delete employee</a></p>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>