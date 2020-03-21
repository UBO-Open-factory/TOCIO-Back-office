<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Tablemesure;

/* @var $this yii\web\View */
/* @var $model app\models\Grandeur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grandeur-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nature')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'formatCapteur')->textInput(['maxlength' => true]) ?>

	<?= /* liste déroulante pour les types de valeurs*/
		$form->field($model, 'type')->dropDownList(["float(5,3)" => "Float",
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
