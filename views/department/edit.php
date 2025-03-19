<?php
require_once '../../initialise.php';

$departmentID = (int) ($_GET['id'] ?? 0); // Make it a number

if($departmentID === 0){
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Department.php';

$departmentName = '';

$department = new Department();
$departmentToEdit = $department->getByID($departmentID);


if(!$departmentToEdit) {
    $errorMessage = 'Department not found';
} else {
    $departmentToEdit = $departmentToEdit[0];
    
    $validationFailure = false;
    $departmentName = $departmentToEdit['department_name'] ?? '';
    
}


$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){

    // Input data to ensure inputs value stays if error
    $departmentName = htmlspecialchars($_POST['name']) ?? '';

    $validationErrors = $department->validate($_POST);
    
    if(!empty($validationErrors)) {
        $validationFailure = true;
        $errorMessage = join(', ', $validationErrors);
    } else {
        // update department
        if (!$department->update($_POST, $departmentID)) {
            $errorMessage = 'It was not possible to update the department. Make sure there is an update added, or come back later';
        } else {
            // if successfull
            header("Location: view.php?id=$departmentID");
        }
    }
}


$pageTitle = 'Edit Department';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
?>
<main>
    <a href=<?= BASE_URL . "/views/department/view.php?id=$departmentID"?>>Back</a>
    <?php if(isset($errorMessage)):?>
            <section>
                <p class="error"><?=$errorMessage?></p>
            </section>
    <?php endif; ?>
    <?php if($postRequest && empty($errorMessage)):?>
                <section>
                    <p>Department updated succesfully!</p>
                </section>
    <?php else: ?>
    <form action="edit.php?id=<?=$departmentID?>" method="POST">
        <div>
            <label for="txtDepartmentName">Department name</label>
            <input type="text" id="txtDepartmentName" name="name" value="<?= $departmentName ?>" required>
        </div>
        <div>
            <button type="submit">Update department</button>
        </div>
    </form>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>