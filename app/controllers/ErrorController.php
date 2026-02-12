<?php


namespace app\controllers;

use app\Controller;

class ErrorController extends Controller
{


    public function actionIndex($code = 500, $message = 'Internal Server Error', \Throwable $e = null)
    {

        http_response_code($code);
        $renderFile = isset($e) ? 'trace' : 'error';
        $this->layout = isset($e) ? 'error' : 'main';

        return $this->render($renderFile, [
            'code' => $code,
            'message' => $message,
            'e' => $e
        ]);
    }


}
