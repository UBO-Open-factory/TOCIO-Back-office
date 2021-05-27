<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\relcartesmethod;
use app\models\Method;
use app\models\capteur;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Cartes */
/* @var $form yii\widgets\ActiveForm */

$l_TAB_MethodAssociees = relcartesmethod::find()->select(['id_method'])->where(["id_carte" => $model->id])->column();

$l_TAB_Options = [];
$l_TAB_Options_same = [];
$l_TAB_Options_indisponible = [];
foreach( relcartesmethod::find()->where( ["id_carte" => $model['id'] ] )->all() as $method ) {
	$l_TAB_Options_same[] = explode( "_", method::find()->where( [
			"id" => $method['id_method'] ] )->one()["nom_method"] )[0];
}
foreach( method::find()->select( ['nom_method','id' ] )->indexBy( 'id' )->column() as $id => $nature ) {
	
	// si la grandeur n'est pas déjà associée à cette carte, on la met dans la liste
	if( ! in_array( $id, $l_TAB_MethodAssociees ) ) {
		if( ! in_array( explode( "_", $nature )[0], $l_TAB_Options_same ) ) {
			$l_TAB_Options[] = '<option value="'.$id.'">'.$nature.'</option>';
		} else {
			$l_TAB_Options_indisponible[] = $nature;
		}
	}
}
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
							"class" => "btn btn-secondary",
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
			if($l_TAB_Options_indisponible)
			{
				echo "<legend class='col row'> Methodes indisponibles</legend>";
				echo "Ces méthodes sont conçu pour des capteurs déjà mis en place pour cette carte, ils ne sont donc pas disponible. Pour les ajouter veuillez supprimer les méthodes comportant le même capteur.<br><br>";
			}
			foreach($l_TAB_Options_indisponible as $indis)
			{
				echo "<div class='btn btn-info' style='padding: 5px;color:black'>";
				echo $indis;
				echo "</div>";
			}
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
			foreach(relcartesmethod::find()->where(["id_carte" => $model['id']])->all() as $method) {
				$l_STR_BtnWarning = Html::tag("span", "", ["class" => "glyphicon glyphicon-exclamation-sign"]);


				$l_STR_BtnModify = Html::tag("span", "", ["class" => "glyphicon glyphicon-cog "]);
				$l_STR_BtnModify = Html::a($l_STR_BtnModify,	
																[
	 		  				         								"/method/update",
 		  				         									"id" => $method['id_method']
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
	 		  				         								"/relcartesmethod/delete",
 		  				         									"id_carte" => $model->id,
 		  				         									"id_method" => $method['id_method']],
	 				  				         					[
	 				  				         						'data-pjax' => "0",
		 		  				         							"aria-label" => "Supprimer",
		 		  				         							"title" => "Supprimer",
		 		  				         							"data-confirm" => "Êtes-vous sur de vouloir dissocier cette méthode à cette carte ?",
				  				         							"data-method"=>"post"
			  				         							]);

				echo "<div class='row'>";
				echo "<div class='col'>" . method::find()->where(["id" => $method["id_method"]])->one()["nom_method"] . "</div>";
				echo "<div class='col'>" . explode("_",method::find()->where(["id" => $method["id_method"]])->one()["nom_method"])[0] . "</div>";
				echo "<div class='col-1'>" . $l_STR_BtnDelete . "</div>";
				if( !in_array( str_replace( " ", "", $model['nom'] ), explode( "_", method::find()->where( ["id" => $method["id_method"] ] )->one()["nom_method"] ) ) ) {
					echo "<div class='col-2' id='methodwarninglink'>".$l_STR_BtnWarning." <h8 style='color:orange'>Warning</h></div>";
				} else {
					echo "<div class='col-2'> </div>";
				}
				
				echo "<div class='col-1'>" . $l_STR_BtnModify . "</div>";

				echo "<br>";
				echo "</div>";
				echo "<div id='sortiemethodwarninglink'>";
				echo "</div>";
			}
			?>
		</div>
		<?php
		?>
	    <div class="col-sm-12 ">
	    	<div class="pull-right">
	        	<?= Html::submitButton('Save', ['class' => 'btn btn-light']) ?>
	    	</div>
	    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
