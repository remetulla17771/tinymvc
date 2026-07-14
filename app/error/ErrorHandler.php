<?php

namespace app\error;

use Throwable;

class ErrorHandler
{
    public bool $debug = true;
    private bool $handling = false;

    public function register(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '0');

        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleException(Throwable $e): void
    {
        if ($this->handling) {
            http_response_code(500);
            echo 'Fatal error';
            exit;
        }

        $this->handling = true;
        $this->renderException($e);
    }

    public function handleError(
        int    $severity,
        string $message,
        string $file,
        int    $line
    ): bool
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [
                E_ERROR,
                E_PARSE,
                E_CORE_ERROR,
                E_COMPILE_ERROR
            ])) {
            $e = new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );
            $this->renderException($e);
        }
    }

    protected function renderException(Throwable $e): void
    {
        http_response_code(500);

        if ($this->debug) {
            $this->renderDebug($e);
        } else {
            echo 'Internal Server Error';
        }

        exit;
    }

    protected function renderDebug(Throwable $e): void
    {
        $file = dirname(__DIR__) . '/error/views/exception.php';
        require $file;
    }
}
