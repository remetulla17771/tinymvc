<?php

namespace app;

use app\controllers\ErrorController;

class ErrorHandler
{

    private static bool $handling = false;

    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleException(\Throwable $e): void
    {
        self::render($e);
    }

    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): bool {
        // превращаем error в exception
        throw new \ErrorException($message, 500, $severity, $file, $line);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [
                E_ERROR,
                E_PARSE,
                E_CORE_ERROR,
                E_COMPILE_ERROR,
                E_RECOVERABLE_ERROR,
                E_CORE_WARNING,
                E_COMPILE_WARNING
            ])) {
            self::render(new \ErrorException(
                $error['message'],
                500,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }

//    public static function handleShutdown(): void
//    {
//        $error = error_get_last();
//
//        if (!$error || $error['type'] !== E_PARSE) {
//            return;
//        }
//
//        while (ob_get_level()) {
//            ob_end_clean();
//        }
//
//        http_response_code(500);
//
//        require_once __DIR__ . '/controllers/ErrorController.php';
//
//        $controller = new \app\controllers\ErrorController();
//        echo $controller->actionIndex(
//            new \ParseError($error['message']),
//            $error['file'],
//            $error['line']
//        );
//        exit;
//    }




    private static function render(\Throwable $e): void
    {
        $code = $e->getCode();
        if ($code < 400 || $code >= 600) {
            $code = 500;
        }
        http_response_code(500);
        if (self::$handling) {
            http_response_code(500);
            echo 'Critical error';
            exit;
        }


        self::$handling = true;

        // === ЛОГ ===
        self::log($e, $code);

        // === РЕНДЕР ===
        $controller = new ErrorController();
        echo $controller->actionIndex($code, $e->getMessage(), $e);

        exit;
    }

    public static function log(\Throwable $e, int $code): void
    {
        $dir = dirname(__DIR__) . '/runtime/logs';

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // === GLOBAL ID ===
        $counterFile = $dir . '/error.counter';

        if (file_exists($counterFile)) {
            $id = (int) file_get_contents($counterFile) + 1;
        } else {
            $id = 1;
        }

        file_put_contents($counterFile, $id, LOCK_EX);

        // === FILE ROTATION ===
        $chunkSize = 5000;
        $fileIndex = (int) ceil($id / $chunkSize);

        $fileName = $fileIndex === 1
            ? 'error.json'
            : 'error_' . $fileIndex . '.json';

        $file = $dir . '/' . $fileName;

        $record = [
            'id'      => $id,
            'time'    => date('Y-m-d H:i:s'),
            'code'    => $code,
            'type'    => get_class($e),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ];

        // === READ CURRENT FILE ===
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $data = json_decode($content, true);

            if (!is_array($data)) {
                $data = [];
            }
        } else {
            $data = [];
        }

        // 👇 новые сверху
        array_unshift($data, $record);

        file_put_contents(
            $file,
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            LOCK_EX
        );
    }






}
