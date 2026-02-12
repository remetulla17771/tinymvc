<?php

use app\App;
use app\assets\AppAsset;
use app\helpers\Alert;
use app\helpers\MetaTagManager;
use app\helpers\NavBar;

?>
<!DOCTYPE html>
<html lang="<?= $this->lang->language() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= MetaTagManager::render() ?>
    <title><?= $this->title ?></title>
    <?php (new AppAsset)->registerCss(); ?>
</head>
<body class="d-flex flex-column">

<main class="container">
    <?= $content ?>
</main>

</body>
</html>
