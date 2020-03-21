<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tablemesure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tablemesure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(["float(5,3)" => "Float",
    		"int(10)" => "Integer",
    		"real" => "Réel",
    		"text" => "Text",
    		"varchar" => "Varchar",
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
