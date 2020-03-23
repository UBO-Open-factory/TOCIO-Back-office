<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Localisationmodule;

/* @var $this yii\web\View */
/* @var $model app\models\Module */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-form">

    <?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-sm-6">
		    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-sm-6">
		    <?= $form->field($model, 'idCapteur')->textarea(['rows' => 2]) ?>
		</div>
		<div class="col-sm-6">
		    <?= $form->field($model, 'identifiantReseau')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-sm-6">
		    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
		</div>		
		<div class="col-sm-6">
		    <?php	/* Liste déroulante des Localisations */ 
		    echo $form->field($model, 'idLocalisationModule')->dropDownList(
		    	Localisationmodule::find()->select(['description','id'])->indexBy('id')->column()
		    ) 
		    ?>
		</div>		
		<div class="col-sm-6">
		    <?= $form->field($model, 'positionCapteur')->textarea(['rows' => 6]) ?>
		</div>
		<div class="col-sm-6">
		    	<?= /* liste déroulante pour les types de valeurs*/
				$form->field($model, 'actif')->dropDownList(["1" => "Oui",
		    		"0" => "Non",
		    ]) ?>
		</div>		
	</div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
