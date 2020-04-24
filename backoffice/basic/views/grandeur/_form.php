<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
		<div class="col-sm-12 ">
	    	<p class="pull-right">
	        	<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
	        </p>
	    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
