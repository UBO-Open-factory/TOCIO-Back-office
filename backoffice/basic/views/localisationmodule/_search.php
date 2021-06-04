<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\LocalisationmoduleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="localisationmodule-search">

    <?php $form = ActiveForm::begin([
    	'action' => Url::to(['index']),
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'coordX') ?>

    <?= $form->field($model, 'coordY') ?>

    <?= $form->field($model, 'coordZ') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'button buttonLocalisation']) ?>
        <?= Html::resetButton('Reset', ['class' => 'button buttonLocalisation']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
