<?php

use app\App;
use app\assets\AppAsset;
use app\helpers\Alert;
use app\helpers\MetaTagManager;
use app\helpers\NavBar;


MetaTagManager::register(['name' => 'description', 'content' => 'Desc']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= MetaTagManager::render() ?>
    <title>New Layout</title>
    <?php (new AppAsset)->registerCss(); ?>
</head>
<body class="d-flex flex-column h-80">


<header>
    <?php
    new NavBar([
        'brandLabel' => $this->config('appName'),
        'brandUrl' => '/',
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top', 'ember' => 'fff'],
        'ulClass' => 'navbar-nav navbar-collapse justify-content-end nav',
        'items' => [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'About', 'url' => '/site/about'],
            ['label' => 'Create', 'url' => '/site/add']
        ],
    ]);
    ?>
</header>

<main class="container" style="height: 100vh; margin-top: 80px;">

    <?= Alert::getAll() ?>

    <?= $content ?>
</main>


<footer id="footer" class="mt-auto py-2 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; <?= date('Y') ?> My MVC App</p></div>
            <div class="col-md-6 text-center text-md-end"><?= App::powered() ?></div>
        </div>
    </div>
</footer>


<?php (new AppAsset)->registerJs(); ?>
</body>
</html>
