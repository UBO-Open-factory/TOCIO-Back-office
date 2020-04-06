<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Relmodulecapteur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="relmodulecapteur-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idModule')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idCapteur')->textInput() ?>

    <?= $form->field($model, 'nomcapteur')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'x')->textInput() ?>

    <?= $form->field($model, 'y')->textInput() ?>

    <?= $form->field($model, 'z')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
