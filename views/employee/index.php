<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Employee.php';

$searchText = trim($_GET['search'] ?? '');

$employee = new Employee();

if($searchText === ''){
    $employees = $employee->getAll();
} else {
    $searchText = htmlspecialchars($searchText);
    $employees = $employee->search($searchText);
}

if(!$employees){
    $errorMessage = 'There was an error, while retrieving data';
}

$pageTitle = 'Employees';
include_once ROOT_PATH . '/public/header.php';
include_once ROOT_PATH . '/public/nav.php';
?>
    <nav>
        <ul>
            <li><a href="<?=BASE_URL . '/views/employee/add.php' ?>">Add employee</a></li>
        </ul>
    </nav>
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
    </main>
  <?php include_once ROOT_PATH . '/public/footer.php';