<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use app\models\Grandeur;
use yii\helpers\BaseHtml;
use app\models\Relcapteurgrandeur;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Capteur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="capteur-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>
	<div class="row">
		<div class="col-sm-4 bottom-align">
		    <?= $form->field($model, 'nom')->textarea(['rows' => 1]) ?>
		</div>
		<div class="col-sm-7 col-sm-offset-1 bottom-align">
			<label>Grandeur</label>
			<div class="input-group">
				<select class="custom-select" id="capteur-idgrandeurs" name="Capteur[idGrandeurs]">
					<?php 
					$l_TAB_Options = Grandeur::find()->select(['nature','id'])->indexBy('id')->column();
					foreach( $l_TAB_Options as $id => $nature)
						{
						echo '<option value="'. $id .'">' . $nature. '</option>';
						}
					?>
				</select>
				<div class="input-group-append">
					<?php echo Html::tag("button", "<i class='glyphicon glyphicon-plus'></i> Associer cette Grandeur", 
								["class" => "button buttonGrandeur",
									"type" => "button",
									"id" => "btnAddGrandeur"]);?>
				</div>
			</div>
		</div>
		
	    <div class="col-sm-12 ">
	    	<p class="pull-right">
	        	<?= Html::submitButton('Enregistrer', ['class' => 'button buttonCapteur']) ?>
	        </p>
	    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
