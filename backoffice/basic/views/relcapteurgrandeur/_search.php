<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\RelcapteurgrandeurSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="relcapteurgrandeur-search">

    <?php $form = ActiveForm::begin([
    	'action' => Url::base().Url::to(['index']),
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idCapteur') ?>

    <?= $form->field($model, 'idGrandeur') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
