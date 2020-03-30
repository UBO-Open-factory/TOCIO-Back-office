<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Localisationmodule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="localisationmodule-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-sm-12">
		    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
		</div>
		<div class="col-sm-4">
		    <?= $form->field($model, 'coordX')->textInput() ?>
		</div>
		<div class="col-sm-4">
		    <?= $form->field($model, 'coordY')->textInput() ?>
		</div>
		<div class="col-sm-4">
		    <?= $form->field($model, 'coordZ')->textInput() ?>
		</div>
	</div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
