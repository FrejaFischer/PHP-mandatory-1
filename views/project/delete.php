<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Project.php';

$projectID = (int) ($_GET['id'] ?? 0); // Make it a number

// relocate if no id is given
if($projectID === 0){
    header('Location: index.php');
    exit;
}

// Find project to delete
$project = new Project();
$projectToDelete = $project->getByID($projectID);

if(!$projectToDelete) {
    $errorMessage = 'Project not found';
} else {
    $projectToDelete = $projectToDelete[0];
}

// Handle delete request
$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){

    if(!isset($_POST['checkConfirm'])) {
        $errorMessage = 'Please confirm the deletion';
        Logger::logText('unsuccessfull in deleting project. Reason: Tried to without checkbox');
    } else {
        if($project->delete($projectID)) {
            header('Location:' . BASE_URL);
        } else {
            $errorMessage ='Could not delete project';
        }
    }
}

$pageTitle = 'Delete Project';
include_once ROOT_PATH . '/public/header.php';
?>
<main>
    <a href="view.php?id=<?=$projectID?>">&ShortLeftArrow; Back</a>
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?=$errorMessage?></p>
        </section>
    <?php else: ?>
        <p><strong>Name: </strong><?=$projectToDelete['project_name'] ?></p>
        <form action="delete.php?id=<?=$projectID?>" method="POST" class="deleteForm">
            <div>
                <label for="confirm_dlt">Confirm you want to delete this project</label>
                <input type="checkbox" id="confirm_dlt" name="checkConfirm" value="confirmed" required/>
            </div>
            <button type="submit" class="primary_btn">Delete permenently</button>
        </form>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>