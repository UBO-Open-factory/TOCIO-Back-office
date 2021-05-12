<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\relcartesmethod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="relcartesmethod-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_carte')->textInput() ?>

    <?= $form->field($model, 'id_method')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
