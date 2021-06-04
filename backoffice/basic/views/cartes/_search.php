<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CartesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cartes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nom') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'button buttonCarte']) ?>
        <?= Html::resetButton('Reset', ['class' => 'button buttonCarte']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
