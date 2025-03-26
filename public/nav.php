    <nav>
        <ul>
            <li><a href="<?=BASE_URL ?>" class=<?=$pageTitle === 'Company' ? 'current' : ''?>>Home</a></li>
            <li><a href="<?=BASE_URL . '/views/employee' ?>" class=<?=$pageTitle === 'Employees' ? 'current' : ''?>>Employees</a></li>
            <li><a href="<?=BASE_URL . '/views/department' ?>" class=<?=$pageTitle === 'Departments' ? 'current' : ''?>>Departments</a></li>
            <li><a href="<?=BASE_URL . '/views/project' ?>" class=<?=$pageTitle === 'Projects' ? 'current' : ''?>>Projects</a></li>
        </ul>
    </nav>