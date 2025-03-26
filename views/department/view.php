<?php
require_once '../../initialise.php';


$departmentID = (int) ($_GET['id'] ?? 0); // Make it a number

if($departmentID === 0){
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Department.php';
require_once ROOT_PATH . '/classes/Employee.php';

$department = new Department();
$department = $department->getByID($departmentID);

if(!$department) {
    $errorMessage = 'There was an error';
} else {
    $department = $department[0];
}

$employee = new Employee();
$employees = $employee->getAllByDepartment($departmentID);


$pageTitle = 'Department';
include_once ROOT_PATH . '/public/header.php';
?>
<main>
    <a href="<?=BASE_URL . '/views/department'?>">&ShortLeftArrow; Back</a>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage?></p>
        </section>
    <?php else: ?>
        <p><strong>Name: </strong><?=$department['department_name'] ?></p>
        <p><strong>Employees:</strong></p>
        <?php if($employees):?>
            <ul>
                <?php foreach ($employees as $employee):?>
                    <li><a href=<?= BASE_URL . "/views/employee/view.php?id={$employee['employee_ID']}"?>><?=$employee['last_name'] . ', ' .$employee['first_name']?></a></li>
                <?php endforeach;?>
            </ul>
        <?php else:?>
            <p>No employees connected to this department</p>
        <?php endif;?>
        <div class="line"></div>
        <div class="flex">
            <p><a href=<?= BASE_URL . "/views/department/edit.php?id=$departmentID"?>>Edit department</a></p>
            <p><a href=<?= BASE_URL . "/views/department/delete.php?id=$departmentID"?>>Delete department</a></p>
        </div>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>