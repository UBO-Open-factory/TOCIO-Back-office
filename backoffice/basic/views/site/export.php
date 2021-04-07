<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\tocioRegles;
use app\models\Grandeur;
use yii\helpers\ArrayHelper;
use Codeception\Module;



$this->title = 'Exportation de Mesures au format CSV';
$this->params['breadcrumbs'][] = $this->title;

$tableMesures = Grandeur::find()->all();
$listData = ArrayHelper::map( $tableMesures, 'tablename', 'nature' );

// @todo pouvoir faire un filtre par module
?>
<div class="tablemesure-index">

	<h1><?= Html::encode($this->title) ?></h1>


	<p>
		Cette page permet de faire une exportaiton des données collectées pour
		une <i>Grandeur</i> donnée au format CSV.
	</p>
	<p>Il est également possible de cumuler les données selon certain critères pré-définis.</p> 
	
	<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]);	?>
	<div class="row">
		<div class="col-sm-3">
		<?= /* Liste déroulante des tables des Mesures */
		$form->field( $model, 'tableName' )->dropDownList( 
				$listData
		);
		?>
		</div>
		<div class="col-sm-3">
		<?= /* Liste déroulante des Momdules */
		$form->field( $model, 'moduleName' )->dropDownList( 
				['all' => 'Tous les modules' ]
		);
		?>
		</div>
		<div class="col-sm-3">
		<?= /* Liste déroulante des tables des Mesures */
		$form->field( $model, 'cumulBy' )->dropDownList( 
					['none' => 'Aucun cumul',
					'day' => 'Jour',
					'month' => 'Mois',
					'year' => 'Année' ]
		);
		?>
		</div>
		<div class="col-sm-3">
			<br />
		<?= Html::submitButton('Exporter en CSV', ['class' => 'btn btn-primary', 'name' => 'Upload']) ?>
	   </div>
	</div>
	<?php ActiveForm::end() ?>
	
</div>