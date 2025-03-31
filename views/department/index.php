<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Department.php';

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['searchBtn'])) {
        $searchText = trim($_GET['search'] ?? '');
    } else {
        $searchText = '';
    }
}

$department = new Department();

if(!isset($searchText)){
    $departments = $department->getAll();

    // check if departments were found
    if(!$departments){
        $errorMessage = 'There was an error, while retrieving departments';
    }

} else {
    $searchText = htmlspecialchars($searchText);
    $departments = $department->search($searchText);

    // Check if any departments matched search
    if(count($departments) < 1) {
        $message = 'No departments found';
    }
}

$pageTitle = 'Departments';
include_once ROOT_PATH . '/public/header.php';
?>
    <main>
        <?php if(isset($errorMessage)):?>
            <section>
                <p class="error"><?=$errorMessage?></p>
            </section>
        <?php else:?>
            <form action="index.php" method="GET" class="searchForm">
                <div>
                    <label for="txtSearch">Search for departments</label>
                    <input type="search" id="txtSearch" name="search" value=<?=$searchText ? $searchText : ""?>>
                </div>
                <div>
                    <button type="submit"  name="searchBtn" class="primary_btn">Search</button>
                    <button type="submit"  name="resetBtn">Reset</button>
                </div>
            </form>
            <a href="<?=BASE_URL . '/views/department/add.php' ?>">Add department</a>
            <?php if(isset($message)):?>
                <p><?=$message?></p>
            <?php else:?>
                <section>
                    <?php foreach($departments as $department): ?>
                        <article>
                            <p><strong>Name: </strong><?=$department['name']?></p>
                            <p><a href=<?=BASE_URL . "/views/department/view.php?id={$department['department_ID']}"?>>View details</a></p>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif;?>
        <?php endif; ?>
    </main>
  <?php include_once ROOT_PATH . '/public/footer.php';