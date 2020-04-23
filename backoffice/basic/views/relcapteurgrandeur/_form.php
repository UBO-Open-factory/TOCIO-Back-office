<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Relcapteurgrandeur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="relcapteurgrandeur-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idCapteur')->textInput() ?>

    <?= $form->field($model, 'idGrandeur')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
