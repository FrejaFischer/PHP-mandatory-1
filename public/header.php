<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" href=<?=BASE_URL . '/css/styles.css' ?>>
    <link rel="stylesheet" href=<?=BASE_URL . '/css/nav.css' ?>>
</head>
<body>
    <header>
        <h1><?=$pageTitle ?></h1>
        <?php include_once ROOT_PATH . '/public/nav.php';?>
    </header>