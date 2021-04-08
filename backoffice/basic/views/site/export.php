<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\tocioRegles;
use nex\datepicker\DatePicker;



$this->title = 'Exportation de Mesures au format CSV';
$this->params['breadcrumbs'][] = $this->title;

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
		<div class="col-sm-2">
		<?=  /* Filtrage par date (Date de début) 
		@see https://www.yiiframework.com/extension/yii2-datepicker */
		$form->field($model, 'dateStart')->widget( DatePicker::className(), [
		        'addon' => false,
		        'size' 	=> 'sm',
		        'clientOptions' => [
			        'minDate' 		=> $dateMin,
			        'maxDate' 		=> $dateMax,
			        'format' 		=> "YYYY-MM-DD",
		        ],
		]);?>
		</div>
		<div class="col-sm-2">
		<?= /* Filtrage par date (Date de fin)
		@see https://www.yiiframework.com/extension/yii2-datepicker */
		$form->field($model, 'dateEnd')->widget( DatePicker::className(), [
				'addon'	=> false,
				'size' 	=> 'sm',
				'clientOptions' => [
					'minDate' 		=> $dateMin,
					'maxDate' 		=> $dateMax,
					'format' 		=> "YYYY-MM-DD",
				],
		]);?>
		</div>
		<div class="col-sm-3">
		<?= /* Liste déroulante des tables des Mesures */
		$form->field( $model, 'tableName' )->dropDownList( 
				$listMesures
		);
		?>
		</div>
		<div class="col-sm-3">
		<?= /* Liste déroulante des Modules */
		$form->field( $model, 'moduleName' )->dropDownList( 
				['all' => 'Tous les modules',
				$listModules
				]
		);
		?>
		</div>
		<div class="col-sm-3">
		<?= /* Liste déroulante avc les cumul possibles */
		// @todo pouvoir cocher des items dans la liste
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