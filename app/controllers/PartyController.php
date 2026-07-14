<?php
namespace app\controllers;

use app\Controller;

class PartyController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
