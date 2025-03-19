<?php
require_once '../../initialise.php';


$employeeID = (int) ($_GET['id'] ?? 0); // Make it a number

if($employeeID === 0){
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Employee.php';
require_once ROOT_PATH . '/classes/Project.php';

$employee = new Employee();
$employee = $employee->getByID($employeeID);

if(!$employee) {
    $errorMessage = 'No employee found';
} else {
    $employee = $employee[0];
}

$project = new Project();
$projects = $project->getAllByEmployeeID($employeeID);


$pageTitle = 'Employee';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
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
        <p><strong>Projects: </strong></p>
        <?php if(count($projects) < 1):?>
            <p>Employee is not connected to any projects</p>
        <?php else:?>
            <ul>
                <?php foreach($projects as $project):?>
                    <li><a href=<?= BASE_URL . "/views/project/view.php?id={$project['project_id']}"?>> <?=$project['name']?> </a></li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>
        <p><a href=<?= BASE_URL . "/views/employee/edit.php?id=$employeeID"?>>Edit employee</a></p>
        <p><a href=<?= BASE_URL . "/views/employee/delete.php?id=$employeeID"?>>Delete employee</a></p>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>