<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Relcapteurgrandeur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="relcapteurgrandeur-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>

    <?= $form->field($model, 'idCapteur')->textInput() ?>

    <?= $form->field($model, 'idGrandeur')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
