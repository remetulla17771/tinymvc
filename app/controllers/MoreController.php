<?php
namespace app\controllers;
use app\App;
use app\AuthService;
use app\Controller;
use app\helpers\Alert;
use app\models\Shezhire;
use app\models\User;
use app\Response;


class MoreController extends Controller {

//    public string $layout = 'new';
    public function actionIndex() {

        $user = User::find()->all();

        return $this->render('index', [
            'id' => 123,
            'users' => $user
        ]);
    }


}
