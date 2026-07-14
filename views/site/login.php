<?php


use app\helpers\ActiveForm;

$this->title = "Create User";

?>
<h1>Create</h1>

<?php ActiveForm::begin("post", [
    'enctype' => 'multipart/form-data'
]); ?>

<?= ActiveForm::field($model, 'login') ?>
<?= ActiveForm::field($model, 'password') ?>

<?= ActiveForm::submitButton('Login', ['class' => 'btn btn-primary mt-3']) ?>

<?php ActiveForm::end(); ?>
