<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TablemesureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tablemesure-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'timestamp') ?>

    <?= $form->field($model, 'valeur') ?>

    <?= $form->field($model, 'posX') ?>

    <?= $form->field($model, 'posY') ?>

    <?php // echo $form->field($model, 'posZ') ?>

    <?php // echo $form->field($model, 'identifiantModule') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
