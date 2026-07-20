<?php

namespace modules\admin\controllers;

use app\Controller;

class DefaultController extends Controller
{
    public string $layout = 'app';
    public function actionIndex()
    {
        return $this->render('index');
    }
}
