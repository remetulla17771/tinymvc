<?php

namespace modules\grave\controllers;

use app\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
