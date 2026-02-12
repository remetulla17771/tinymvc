<?php

use app\helpers\GridView;
use app\helpers\Html;



?>


<h1>More Index</h1>
<?= GridView::widget([
    'dataProvider' => $users,
    'columns' => [
        'id',
        'login',
        'password',
        [
            'attribute' => 'token',
            'value' => function ($data) {
                return "Token: " . $data->token;
            }
        ],
        [
            'label' => 'Action',
            'value' => function ($data) {
                return Html::a("Update", ['/site/update', 'id' => $data->id], ['class' => 'btn btn-warning'])
                    . " " . Html::a('View', ['/site/view', 'id' => $data->id], ['class' => 'btn btn-success'])
                    . " " . Html::a('Delete', ['/site/delete', 'id' => $data->id], ['class' => 'btn btn-danger', 'data-confirm' => 'Are you sure you want to delete this?']);
//                return '<a href="/site/user?id=' . $data->id . '">Update</a> | <a href="/user/delete?id=' . $data->id . '">Delete</a>';
            }
        ]
    ]
]);
?>

<div>

    <div>
        <?php foreach ($users as $user) { ?>
            <div>
                <?= $user->login ?>
            </div>
        <?php } ?>
    </div>

</div>