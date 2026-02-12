<?php

namespace app;

use PDO;

class Db
{
    private static ?PDO $pdo = null;

    public static function getInstance(): PDO
    {

        try {
            $config = require __DIR__ . '/config/db.php';

            self::$pdo = new PDO(
                $config['dsn'],
                $config['user'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

            return self::$pdo;

        } catch (\Throwable $e){
            $controller = new \app\controllers\ErrorController();
            $code = $e->getCode();
            if ($code < 400 || $code >= 600) {
                $code = 500;
            }

            // ✅ РЕГИСТРИРУЕМ ОБРАБОТЧИК ОШИБОК
            \app\ErrorHandler::log($e, $code);

            echo $controller->actionIndex(
                $code,
                $e->getMessage(),
                $e
            );
            die;
        }


    }
}
