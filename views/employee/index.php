<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Employee.php';

$searchText = trim($_GET['search'] ?? '');

$employee = new Employee();

if($searchText === ''){
    $employees = $employee->getAll();

    if(!$employees){
        $errorMessage = 'There was an error, while retrieving the employees';
    }
} else {
    $searchText = htmlspecialchars($searchText);
    $employees = $employee->search($searchText);

     // Check if any employee matched search
     if(count($employees) < 1) {
        $message = 'No employees found';
    }
}

$pageTitle = 'Employees';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
?>
    <main>
        <?php if(isset($errorMessage)):?>
            <section>
                <p class="error"><?=$errorMessage?></p>
            </section>
        <?php else:?>
            <form action="index.php" method="GET">
                <div>
                    <label for="txtSearch">Search</label>
                    <input type="search" id="txtSearch" name="search">
                </div>
                <div>
                    <button type="submit">Search</button>
                </div>
            </form>
            <a href="<?=BASE_URL . '/views/employee/add.php' ?>">Add employee</a>
            <?php if(isset($message)):?>
                <p><?=$message?></p>
            <?php else:?>
                <section>
                    <?php foreach($employees as $employee): ?>
                        <article>
                            <p><strong>First name: </strong><?=$employee['cFirstName']?></p>
                            <p><strong>Last name: </strong><?=$employee['cLastName']?></p>
                            <p><strong>Birth date: </strong><?=$employee['dBirth']?></p>
                            <p><a href=<?=BASE_URL . "/views/employee/view.php?id={$employee['nEmployeeID']}"?>>View details</a></p>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
  <?php include_once ROOT_PATH . '/public/footer.php';