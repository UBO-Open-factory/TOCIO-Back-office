<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tablemesure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tablemesure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'timestamp')->textInput() ?>

    <?= $form->field($model, 'valeur')->textInput() ?>

    <?= $form->field($model, 'posX')->textInput() ?>

    <?= $form->field($model, 'posY')->textInput() ?>

    <?= $form->field($model, 'posZ')->textInput() ?>

    <?= $form->field($model, 'identifiantModule')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
