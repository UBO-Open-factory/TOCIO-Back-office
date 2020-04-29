<?php

use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use app\models\Grandeur;
use yii\helpers\BaseHtml;
use app\models\Relcapteurgrandeur;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Capteur */
/* @var $form yii\widgets\ActiveForm */

// Liste des Grandeurs associées au Capteur
$l_TAB_GrandeurAssociees = Relcapteurgrandeur::find()->select(['idGrandeur'])->where(["idCapteur" => $model->id])->column();

// Construction de la liste déroulante
$l_TAB_Options = [];
foreach( Grandeur::find()->select(['nature','id'])->indexBy('id')->column() as $id => $nature){
	// si la grandeur n'est pas déjà associée à ce Capteur, on la met dans la liste
	if( !in_array($id, $l_TAB_GrandeurAssociees)) {
		$l_TAB_Options[] = '<option value="'. $id .'">' . $nature. '</option>';
	}
}
?>

<div class="capteur-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-sm-4 bottom-align">
		    <?= $form->field($model, 'nom')->textarea(['rows' => 1]) ?>
		</div>
		<?php if( count($l_TAB_Options) > 0 ) {?>
		<div class="col-sm-7 col-sm-offset-1 bottom-align">
			<label>Grandeur</label>
			<div class="input-group">
				<select class="custom-select" id="capteur-idgrandeurs" name="Capteur[idGrandeurs]">
					<?php echo  implode("", $l_TAB_Options);
					?>
				</select>
				<div class="input-group-append">
					<?php echo Html::tag("button", "<i class='glyphicon glyphicon-plus'></i> Associer cette Grandeur", 
								["class" => "btn btn-secondary",
									"type" => "button",
									"id" => "btnAddGrandeur"]);?>
				</div>
			</div>
		</div>
		<?php }?>
			
			
		<div class="col-sm-12">
			<fieldset>		
				<legend>Grandeurs actuellement associés</legend>
				<?php 
				foreach(Relcapteurgrandeur::find()->where(["idCapteur" => $model->id])->all()  as $l_OBJ_CapteurGrandeurs){
					$l_STR_BtnDelete = Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
					$l_STR_BtnDelete = Html::a($l_STR_BtnDelete,
	 		  				         							["/relcapteurgrandeur/delete",
	 		  				         									"idCapteur" => $model->id,
	 		  				         									"idGrandeur" => $l_OBJ_CapteurGrandeurs->idGrandeur],
	 				  				         					['data-pjax' => "0",
	 		  				         							"aria-label" => "Supprimer",
	 		  				         							"title" => "Supprimer",
	 		  				         							"data-confirm" => "Êtes-vous sûr de vouloir supprimer cette Grandeur pour ce Capteur ?",
			  				         							"data-method"=>"post"]);
					echo '<div class="row">';
					echo '	<div class="col">'.$l_OBJ_CapteurGrandeurs->idGrandeur0->nature.'</div>';
					echo '	<div class="col">'.$l_OBJ_CapteurGrandeurs->idGrandeur0->formatCapteur.'</div>';
					echo '	<div class="col">'.$l_STR_BtnDelete.'</div>';
					echo '</div>';
				}
				?>
			</fieldset>
		</div>
		
	    <div class="col-sm-12 ">
	    	<p class="pull-right">
	        	<?= Html::submitButton('Save', ['class' => 'btn btn-info']) ?>
	        </p>
	    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
