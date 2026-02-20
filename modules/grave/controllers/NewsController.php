<?php
namespace modules\grave\controllers;

use app\Controller;
use app\helpers\Alert;
use app\helpers\Pagination;
use app\models\News;

class NewsController extends Controller
{
    public function actionIndex()
    {
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) $page = 1;
        $pageSize = (int)($_GET['per-page'] ?? 10);
        if ($pageSize < 1) $pageSize = 10;
        if ($pageSize > 100) $pageSize = 100;

        $query = News::find()->orderBy(['id' => 'DESC']);
        $total = $query->count();
        $pagination = new Pagination($total, $pageSize, $page);

        $models = $query
            ->limit($pagination->pageSize)
            ->offset($pagination->getOffset())
            ->all();

        return $this->render('index', ['models' => $models, 'pagination' => $pagination]);
    }

    public function actionView($id)
    {
        $model = News::find()->where(['id' => (int)$id])->one();
        if (!$model) throw new \Exception('Not found', 404);
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new News();
        if ($this->request->isPost() && $model->load($this->request->post())) {
            $model->save();
            Alert::add('success', 'Created');
            return $this->redirect(['/grave/news/index']);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = News::find()->where(['id' => (int)$id])->one();
        if (!$model) throw new \Exception('Not found', 404);
        if ($this->request->isPost() && $model->load($this->request->post())) {
            $model->save();
            Alert::add('success', 'Updated');
            return $this->redirect(['/grave/news/view', 'id' => $model->id]);
        }
        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = News::find()->where(['id' => (int)$id])->one();
        if ($model) {
            $model->delete();
            Alert::add('warning', 'Deleted');
        } else {
            Alert::add('danger', 'Not found');
        }
        return $this->redirect(['/grave/news/index']);
    }
}
