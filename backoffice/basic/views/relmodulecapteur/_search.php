<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RelmodulecapteurSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="relmodulecapteur-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'idModule') ?>

    <?= $form->field($model, 'idCapteur') ?>

    <?= $form->field($model, 'nomcapteur') ?>

    <?= $form->field($model, 'x') ?>

    <?= $form->field($model, 'y') ?>

    <?php // echo $form->field($model, 'z') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
