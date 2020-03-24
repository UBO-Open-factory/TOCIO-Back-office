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
	<div class="row">
		<div class="col-sm-4">
	    	<?= $form->field($model, 'nature')->textInput(['maxlength' => true]) ?>
		</div>
    	<div class="col-sm-4">
	    	<?= $form->field($model, 'formatCapteur')->textInput(['maxlength' => true]) ?>
	    </div>
		<div class="col-sm-4">
			<?= /* liste déroulante pour les types de valeurs*/
				$form->field($model, 'type')->dropDownList(["float(5,3)" => "Float",
		    		"int(10)" => "Integer",
		    		"real" => "Réel",
		    		"text" => "Text",
		    		"varchar" => "Varchar",
		    ]) ?>
		</div>
	</div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
