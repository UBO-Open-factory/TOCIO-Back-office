<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MethodSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="method-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_capteur') ?>

    <?= $form->field($model, 'id_carte') ?>

    <?= $form->field($model, 'nom_method') ?>

    <?= $form->field($model, 'method_include') ?>

    <?php // echo $form->field($model, 'method_statement') ?>

    <?php // echo $form->field($model, 'method_setup') ?>

    <?php // echo $form->field($model, 'method_read') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'button buttonMethod']) ?>
        <?= Html::resetButton('Reset', ['class' => 'button buttonMethod']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
