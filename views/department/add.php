<?php
require_once '../../initialise.php';
require_once ROOT_PATH . '/classes/Department.php';

$validationFailure = false; // For checking if there is validation failure

$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){
    $department = new Department();

    // Saving values from form to ensure inputs value stays if error accures
    $departmentName = htmlspecialchars($_POST['name']) ?? '';

    $validationErrors = $department->validate($_POST); // Validating form

    if(!empty($validationErrors)) {
        $validationFailure = true;
        $errorMessage = join(', ', $validationErrors);
    } else {
        if (!$department->insert($_POST)) {
            $errorMessage = 'It was not possible to insert the new department';
        }
    }
}

$pageTitle = 'Add Department';
include_once ROOT_PATH . '/public/header.php';
?>
<main>
    <a href="<?=BASE_URL . '/views/department'?>">&ShortLeftArrow; Back</a>
    <?php if(isset($errorMessage)):?>
            <section>
                <p class="error"><?=$errorMessage?></p>
            </section>
    <?php endif; ?>

    <?php if($postRequest && empty($errorMessage)):?>
                <section>
                    <p>Department inserted succesfully!</p>
                </section>
    <?php endif; ?>

    <form action="add.php" method="POST" class="addForm">
        <div>
            <label for="txtName">Department Name</label>
            <input type="text" id="txtName" name="name" maxlength="<?=Department::MAX_NAME_LENGTH ?>" value="<?= isset($errorMessage) ? $departmentName : '' ?>" required >
        </div>
        <div>
            <button type="submit" class="primary_btn">Add department</button>
        </div>
    </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>