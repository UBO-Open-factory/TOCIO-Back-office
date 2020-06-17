<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Grandeur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grandeur-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>
	<div class="row">
		<div class="col-sm-4">
	    	<?= $form->field($model, 'nature')->textInput(['maxlength' => true]) ?>
		</div>
    	<div class="col-sm-4">
	    	<?= $form->field($model, 'formatCapteur')->textInput(['maxlength' => true]) ?>
	    </div>
		<div class="col-sm-4">
			<?php /* liste déroulante pour les types de valeurs*/
				echo $form->field($model, 'type')->dropDownList([
		    		"int(10)" => "Integer",
					"float(5,3)" => "Float",
		    		"real" => "Réel",
		    		"text" => "Text",
		    		"varchar(50)" => "Varchar",
		    ]); 
// 		    echo $form->field($model, 'type')->hiddenInput(['value'=> "Integer"])->label(false);
				?>
		</div>
		<div class="col-sm-12 ">
		        <?= Html::submitButton('Enregistrer', ['class' => 'btn btn-primary pull-right']) ?>
	    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
