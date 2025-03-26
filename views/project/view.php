<?php
require_once '../../initialise.php';


$projectID = (int) ($_GET['id'] ?? 0); // Make it a number

if($projectID === 0){
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Project.php';
require_once ROOT_PATH . '/classes/Employee.php';

$project = new Project();
$project = $project->getByID($projectID);

if(!$project) {
    $errorMessage = 'There was an error';
} else {
    $project = $project[0];
}

$employee = new Employee();
$employees = $employee->getAllByProject($projectID);


$pageTitle = 'Project';
include_once ROOT_PATH . '/public/header.php';
?>
<main>
    <a href="<?=BASE_URL . '/views/project'?>">&ShortLeftArrow; Back</a>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage?></p>
        </section>
    <?php else: ?>
        <p><strong>Name: </strong><?=$project['project_name'] ?></p>
        <h2>Employees</h2>
        <?php if($employees):?>
            <ul>
                <?php foreach ($employees as $employee):?>
                    <li><a href=<?= BASE_URL . "/views/employee/view.php?id={$employee['employee_ID']}"?>><?=$employee['last_name'] . ', ' .$employee['first_name']?></a></li>
                <?php endforeach;?>
            </ul>
        <?php else:?>
            <p>No employees connected to this project</p>
        <?php endif;?>
        <p><a href=<?= BASE_URL . "/views/project/edit.php?id=$projectID"?>>Edit project</a></p>
        <p><a href=<?= BASE_URL . "/views/project/delete.php?id=$projectID"?>>Delete project</a></p>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>