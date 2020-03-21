<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Localisationmodule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="localisationmodule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'coordX')->textInput() ?>

    <?= $form->field($model, 'coordY')->textInput() ?>

    <?= $form->field($model, 'coordZ')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
