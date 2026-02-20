<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/ErrorHandler.php';


use app\ErrorHandler;

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', '0');

session_start();


ErrorHandler::register();

// ⚠️ автозагрузка ПОСЛЕ ErrorHandler
//spl_autoload_register(function ($class) {
//    $prefix = 'app\\';
//    $baseDir = __DIR__ . '/../app/';
//
//    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
//        return;
//    }
//
//    $relativeClass = substr($class, strlen($prefix));
//    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
//    if (file_exists($file)) {
//        require $file;
//    }
//});


//spl_autoload_register(function ($class) {
//    $map = [
//        'app\\'     => __DIR__ . '/../app/',
//        'modules\\' => __DIR__ . '/../modules/',
//    ];
//
//    foreach ($map as $prefix => $baseDir) {
//        if (strncmp($prefix, $class, strlen($prefix)) !== 0) continue;
//
//        $relativeClass = substr($class, strlen($prefix));
//        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
//
//        if (file_exists($file)) {
//            require $file;
//        }
//        return;
//    }
//});

$app = new app\App();

echo $app->run();
