<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Localisationmodule;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Module */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="module-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>

	<div class="row">
		<div class="col-sm-6">
		    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-sm-6">
		    <?= $form->field($model, 'identifiantReseau')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-sm-6">
		    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
		</div>		
		<div class="col-sm-6">
			<div class="row">
				<div class="col-md-7">
				    <?php	/* Liste déroulante des Localisations */ 
				    echo $form->field($model, 'idLocalisationModule')->dropDownList(
				    	Localisationmodule::find()->select(['description','id'])->indexBy('id')->column()
				    	);
				   ?>
				</div>
				<div class="col-md-5">
					<p><br/></p>
					<?php
						echo Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Ajouter une localisation', 
								['localisationmodule/create'], 
								['class' => 'btn btn-primary pull-right']);
		    		?>
				</div>
			</div>
		</div>		

		<div class="col-sm-6">
		    	<?= /* liste déroulante pour les types de valeurs*/
		    	$form->field($model, 'actif')->radioList(["1" => "Oui",
		    		"0" => "Non",
		    ]) ?>
		</div>		
	</div>

    <div class="form-group push-right">
        <?= Html::submitButton('Enregistrer', ['class' => 'btn btn-success pull-right']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
