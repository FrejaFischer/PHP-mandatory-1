<?php
require_once '../../initialise.php';

require_once ROOT_PATH . '/classes/Project.php';

$searchText = trim($_GET['search'] ?? '');

$project = new Project();

if($searchText === ''){
    $projects = $project->getAll();

    // check if projects were found
    if(!$projects){
        $errorMessage = 'There was an error, while retrieving projects';
    }

} else {
    $searchText = htmlspecialchars($searchText);
    $projects = $project->search($searchText);

    // Check if any projects matched search
    if(count($projects) < 1) {
        $message = 'No project found';
    }
}

$pageTitle = 'Projects';
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
            <a href="<?=BASE_URL . '/views/project/add.php' ?>">Add Project</a>
            <?php if(isset($message)):?>
                <p><?=$message?></p>
            <?php else:?>
                <section>
                    <?php foreach($projects as $project): ?>
                        <article>
                            <p><strong>Name: </strong><?=$project['name']?></p>
                            <p><a href=<?=BASE_URL . "/views/project/view.php?id={$project['project_ID']}"?>>View details</a></p>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        <?php endif; ?>
    </main>
  <?php include_once ROOT_PATH . '/public/footer.php';