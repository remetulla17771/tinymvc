<?php
declare(strict_types=1);

namespace app\console;

class MakeModuleCommand implements CommandInterface
{
    public function name(): string { return 'make:module'; }
    public function description(): string { return 'Generate module skeleton (Module.php + controllers + views + layout)'; }

    public function execute(Input $in, Output $out): int
    {
        $id = (string)$in->arg(0, '');
        if ($id === '' || !preg_match('/^[A-Za-z0-9_]+$/', $id)) {
            $out->line("Usage: php bin/console.php make:module admin [--force]");
            return 1;
        }

        $force = $in->has('force');

        $root = dirname(__DIR__, 2);
        $moduleDir = $root . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $id;

        $this->ensureDir($moduleDir . '/controllers');
        $this->ensureDir($moduleDir . '/views/layouts');
        $this->ensureDir($moduleDir . '/views/site');

        $modulePhp = $moduleDir . '/Module.php';
        $siteController = $moduleDir . '/controllers/SiteController.php';
        $layoutMain = $moduleDir . '/views/layouts/main.php';
        $siteIndex = $moduleDir . '/views/site/index.php';

        $moduleCode =
            "<?php\n\n" .
            "namespace modules\\{$id};\n\n" .
            "class Module\n" .
            "{\n" .
            "    // module marker\n" .
            "}\n";

        $controllerCode =
            "<?php\n\n" .
            "namespace modules\\{$id}\\controllers;\n\n" .
            "use app\\Controller;\n\n" .
            "class SiteController extends Controller\n" .
            "{\n" .
            "    public function actionIndex()\n" .
            "    {\n" .
            "        return \$this->render('index');\n" .
            "    }\n" .
            "}\n";

        $layoutCode = <<<'PHP'
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
<body class="d-flex flex-column h-80">


<header>
    <?php

    new NavBar([
        'brandLabel' => $this->config('appName'),
        'brandUrl' => '/site/index',
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
        'ulClass' => 'navbar-nav navbar-collapse justify-content-end nav',
        'items' => [
            ['label' => 'Home', 'url' => '/{MODULE}/site/index'],
            $this->user->isGuest() ? ['label' => 'Login', 'url' => '/site/login'] : ['label' => $this->user->identity('login') . " (Logout)", 'url' => '/site/logout']
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
PHP;
        $layoutCode = str_replace('{MODULE}', $id, $layoutCode);

        $viewCode =
            "<h1>Module '{$id}' works</h1>\n" .
            "<p>Open: <code>/{$id}/site/index</code></p>\n";

        $this->writeFile($modulePhp, $moduleCode, $force);
        $this->writeFile($siteController, $controllerCode, $force);
        $this->writeFile($layoutMain, $layoutCode, $force);
        $this->writeFile($siteIndex, $viewCode, $force);

        $out->line("OK: modules/{$id} created");
        return 0;
    }

    private function ensureDir(string $path): void
    {
        $path = str_replace(['\\'], '/', $path);
        if (!is_dir($path)) mkdir($path, 0777, true);
    }

    private function writeFile(string $path, string $content, bool $force): void
    {
        $path = str_replace(['\\'], '/', $path);
        if (file_exists($path) && !$force) return;
        file_put_contents($path, $content);
    }
}