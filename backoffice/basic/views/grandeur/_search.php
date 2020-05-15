<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\GrandeurSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grandeur-search">

    <?php $form = ActiveForm::begin([
    	'action' => Url::to(['index']),
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nature') ?>

    <?= $form->field($model, 'formatCapteur') ?>

    <?= $form->field($model, 'tablename') ?>

    <?= $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
