<?php
require_once '../../initialise.php';
require_once ROOT_PATH . '/classes/Project.php';

$validationFailure = false; // For checking if there is validation failure

$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){
    $project = new Project();

    // Saving values from form to ensure inputs value stays if error accures
    $projectName = htmlspecialchars($_POST['name']) ?? '';

    $validationErrors = $project->validate($_POST); // Validating form

    if(!empty($validationErrors)) {
        $validationFailure = true;
        $errorMessage = join(', ', $validationErrors);
    } else {
        if (!$project->insert($_POST)) {
            $errorMessage = 'It was not possible to insert the new department';
        }
    }
}

$pageTitle = 'Add Project';
include_once ROOT_PATH . '/public/header.php';
?>
<main>
    <a href="<?=BASE_URL . '/views/project'?>">&ShortLeftArrow; Back</a>
    <?php if(isset($errorMessage)):?>
            <section>
                <p class="error"><?=$errorMessage?></p>
            </section>
    <?php endif; ?>

    <?php if($postRequest && empty($errorMessage)):?>
                <section>
                    <p>Project inserted succesfully!</p>
                </section>
    <?php endif; ?>

    <form action="add.php" method="POST">
        <div>
            <label for="txtName">Project Name</label>
            <input type="text" id="txtName" name="name" maxlength="128" value="<?= isset($errorMessage) ? $projectName : '' ?>" required >
        </div>
        <div>
            <button type="submit">Add project</button>
        </div>
    </form>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>