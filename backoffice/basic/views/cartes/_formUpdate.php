<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Method;
use app\models\Capteur;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Cartes */
/* @var $form yii\widgets\ActiveForm */


$l_TAB_Options = [];
?>


<div class="cartes-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>
    <div class="row">
	    <div class="col-sm-4">
	    	<?= $form->field($model, 'nom')->textarea(['rows' => 1]) ?>
		</div>
		<?php if( count($l_TAB_Options) > 0 ) {?>
		<div class="col-sm-7 col-sm-offset-1 bottom-align">
			<label>Methodes</label>
			<div class="input-group">
				<select class="custom-select" id="Cartes-id_method" name="Cartes[nom_method]">
					<?php echo  implode("", $l_TAB_Options);
					?>
				</select>
				<div class="input-group-append">
					<?php echo Html::tag("button", "<i class='glyphicon glyphicon-plus'></i> Associer cette méthode", 
						[
							"class" => "button buttonCarte",
							"type" => "button",
							"id" => "btnAddMethod"
						]);
					?>
				</div>
			</div>
		</div>
		<?php }?>

		<div class="col-sm-12">
			<?php
		
			echo "<br>";echo "<br>";
			?>
			<div class="row">
				<legend class='col'> Nom method</legend>
				<legend class='col'> Capteur associé</legend>
				<legend class='col-1'> </legend>
				<legend class='col-2'> </legend>
				<legend class='col-1'> </legend>
			</div>
			<br>
			<?php
			foreach(method::find()->where(["id_carte" => $model['id']])->all() as $method)
			{
				$l_STR_BtnWarning = Html::tag("span", "", ["class" => "glyphicon glyphicon-exclamation-sign"]);


				$l_STR_BtnModify = Html::tag("span", "", ["class" => "glyphicon glyphicon-cog "]);
				$l_STR_BtnModify = Html::a($l_STR_BtnModify,	
																[
	 		  				         								"/method/update",
 		  				         									"id" => $method['id']
 		  				         								],
	 				  				         					[
	 				  				         						'data-pjax' => "0",
		 		  				         							"aria-label" => "modifier",
		 		  				         							"title" => "modifier",
				  				         							"data-method"=>"post"
			  				         							]);

				$l_STR_BtnDelete = Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
				$l_STR_BtnDelete = Html::a($l_STR_BtnDelete,
	 		  				         							[
	 		  				         								"/method/delete",
 		  				         									"id" => $method['id']],
	 				  				         					[
	 				  				         						'data-pjax' => "0",
		 		  				         							"aria-label" => "Supprimer",
		 		  				         							"title" => "Supprimer",
		 		  				         							"data-confirm" => "Êtes-vous sur de vouloir supprimer cette méthode ?",
				  				         							"data-method"=>"post"
			  				         							]);

				echo "<div class='row'>";
				echo "<div class='col'>" . method::find()->where(["id" => $method["id"]])->one()["nom_method"] . "</div>";
				echo "<div class='col'>" . explode("_",method::find()->where(["id" => $method["id"]])->one()["nom_method"])[0] . "</div>";
				echo "<div class='col-1'>" . $l_STR_BtnDelete . "</div>";
				echo "<div class='col-2'> </div>";
				
				echo "<div class='col-1'>" . $l_STR_BtnModify . "</div>";
				echo "</div>";
			}
			?>
		</div>
		<?php
		?>
	    <div class="col-sm-12 ">
	    	<div class="pull-right">
	        	<?= Html::submitButton('Save', ['class' => 'button buttonCarte']) ?>
	    	</div>
	    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
