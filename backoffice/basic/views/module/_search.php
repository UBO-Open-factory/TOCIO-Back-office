<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ModuleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-search">

    <?php $form = ActiveForm::begin([
    	'action' => Url::to(['index']),
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'identifiantReseau') ?>

    <?= $form->field($model, 'nom') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'idLocalisationModule') ?>

    <?= $form->field($model, 'actif') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'button buttonModule']) ?>
        <?= Html::resetButton('Reset', ['class' => 'button buttonModule']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
