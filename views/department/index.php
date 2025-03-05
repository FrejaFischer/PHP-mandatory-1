<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Department.php';

$searchText = trim($_GET['search'] ?? '');

$department = new Department();

if($searchText === ''){
    $departments = $department->getAll();
} else {
    $searchText = htmlspecialchars($searchText);
    $departments = $department->search($searchText);
}

if(!$departments){
    $errorMessage = 'There was an error, while retrieving departments';
}

$pageTitle = 'Departments';
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
            <a href="<?=BASE_URL . '/views/department/add.php' ?>">Add department</a>
            <section>
                <?php foreach($departments as $department): ?>
                    <article>
                        <p><strong>Name: </strong><?=$department['name']?></p>
                        <p><a href=<?=BASE_URL . "/views/department/view.php?id={$department['department_ID']}"?>>View details</a></p>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
  <?php include_once ROOT_PATH . '/public/footer.php';