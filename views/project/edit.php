<?php
require_once '../../initialise.php';

$projectID = (int) ($_GET['id'] ?? 0); // Make it a number

if($projectID === 0){
    header('Location: index.php');
    exit;
}

require_once ROOT_PATH . '/classes/Project.php';
require_once ROOT_PATH . '/classes/Employee.php';

$projectName = '';

$project = new Project();
$projectToEdit = $project->getByID($projectID);


// Check if project to edit is found
if(!$projectToEdit) {
    $errorMessage = 'Project not found';
} else {
    $projectToEdit = $projectToEdit[0];
    
    $validationFailure = false;

    // Variable to display current name
    $projectName = $projectToEdit['project_name'] ?? '';
}

$employee = new Employee();
// Get projects connected employees
$employeesInProject = $employee->getAllByProject($projectID);

// Get all employees
$allEmployees = $employee->getAll();
$filteredEmployees = []; // Array with employees currently not in project
$idsToRemove = []; // IDs to be removed from allEmployees
// Add all the current project connected employees IDs to array
foreach($employeesInProject as $employeeInProject) {
    array_push($idsToRemove, $employeeInProject['employee_ID']);
}
// Filter out employees whose ID is in $idsToRemove
$filteredEmployees = array_filter($allEmployees, function ($employee) use ($idsToRemove) {
    return !in_array($employee['employee_ID'], $idsToRemove);
});

$postRequest = $_SERVER['REQUEST_METHOD'] === 'POST';
if($postRequest){

    // Find which form has submitted
    if(isset($_POST['update_project'])) {
        // Update projects name

        // Input data to ensure inputs value stays if error
        $projectName = htmlspecialchars($_POST['name']) ?? '';
    
        $validationErrors = $project->validate($_POST);
        
        if(!empty($validationErrors)) {
            $validationFailure = true;
            $errorMessage = join(', ', $validationErrors);
        } else {
            // update project
            if (!$project->update($_POST, $projectID)) {
                $errorMessage = 'It was not possible to update the project. Make sure there is an update added, or come back later';
            } else {
                // if successfull
                header("Location: view.php?id=$projectID");
            }
        }
    } elseif(isset($_POST['remove_employee'])){
        // Remove employee from project
            
        $employeeID = $_POST['employeeID'];
        // Remove employee from project
        if (!$project->removeEmployee($projectID, $employeeID)) {
            $errorMessage = 'It was not possible to remove the employee from the project.';
        } else {
            // if successfull
            header("Location: view.php?id=$projectID");
        }
    } elseif(isset($_POST['add_employee'])){
        // Add employee to project

        $employeeID = $_POST['new_employee'];
         // Add employee to project
         if (!$project->addEmployee($projectID, $employeeID)) {
            $errorMessage = 'It was not possible to add the employee to the project.';
        } else {
            // if successfull
            header("Location: view.php?id=$projectID");
        }

    } else {
        $validationFailure = true;
        $errorMessage = 'An error occured';
    }

}


$pageTitle = 'Edit Project';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
?>
<main>
    <a href=<?= BASE_URL . "/views/project/view.php?id=$projectID"?>>Back</a>
    <?php if(isset($errorMessage)):?>
            <section>
                <p class="error"><?=$errorMessage?></p>
            </section>
    <?php endif; ?>
    <?php if($postRequest && empty($errorMessage)):?>
                <section>
                    <p>Project updated succesfully!</p>
                </section>
    <?php else: ?>
    <form action="edit.php?id=<?=$projectID?>" method="POST">
        <div>
            <label for="txtProjectName">Project name</label>
            <input type="text" id="txtProjectName" name="name" value="<?= $projectName ?>" required>
        </div>
        <div>
            <button type="submit" name="update_project">Update project name</button>
        </div>
    </form>
    <form action="edit.php?id=<?=$projectID?>" method="POST">
        <p>Employees:</p>
        <?php if(count($employeesInProject) < 1):?>
            <p>No employees connected to project</p>
        <?php else:?>
            <ul>
                <?php foreach($employeesInProject as $employee): ?>
                    <li>
                        <form action="edit.php?id=<?=$projectID?>" method="POST">
                            <div class="flex">
                                <input type="hidden" name="employeeID" value=<?=$employee['employee_ID']?>>
                                <button type="submit" name="remove_employee">Remove</button>
                                <p><?=$employee['last_name'] . ', ' . $employee['first_name'] . ' (' . $employee['department_name'] . ')'?></p>
                            </div>
                        </form>
                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>
    </form>

    <form action="edit.php?id=<?=$projectID?>" method="POST">
        <p>Add employees:</p>
        <p>Employees:</p>
        <select name="new_employee" id="employee">
                <?php foreach($filteredEmployees as $em):?>
                    <option value="<?=$em['employee_ID']?>"><?=$em['lastName'] . ', ' . $em['name'] . ' (' . $em['department_name'] . ')'?></option>
                <?php endforeach?>
            </select>
        <button type="submit" name="add_employee">Add</button>
    </form>
    <?php endif; ?>
</main>

<?php include_once ROOT_PATH . '/public/footer.php'; ?>