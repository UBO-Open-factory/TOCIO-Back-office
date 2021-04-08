<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\tocioRegles;
use nex\datepicker\DatePicker;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use Symfony\Component\DomCrawler\Form;



$this->title = 'Exportation de mesures au format CSV';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="tablemesure-index">

	<h1><?= Html::encode($this->title) ?></h1>


	<p>Il est possible de cumuler les données collectées pour une <i>Grandeur</i> (selon certain critères pré-définis), de filtrer les données par <i>Module</i> et par date.</p>
	<p>Les dates possibles sont celles pour lesquelles il existe des données pour ces Modules.</p> 

	
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
	</div>
	<div class="row">
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
		<div class="col-sm-12">
			<br />
		<?php 
		/* @todo pouvoir afficher les données avant de les télécharger
		 * @bug si on décommente l'affichage de ce bouton, on peut afficher les données, mais
		 * dès qu'on fait un download, il n'est plus possible de faire autre chose qu'un download.
		 * -> Je penses qu'il faudrait rafraichir la page après un download, mais le prolbème c'est
		 * que le navigateur semble garder en mémoire l'entete de type text/csv qui lui a été envoyé.
		Html::submitButton('Afficher', ['class' => 'btn btn-primary', 'name' => 'generateCSV']);
		*/ ?>
		<?= Html::submitButton('Download en CSV', ['class' => 'btn btn-primary pull-right', 'name' => 'downloadCSVExport']) ?>
	   </div>
	</div>
	
	<div class="row">
		<div class="col-xs-12">
			<?php 
				// Affichage des données (si on en a)
				if( count($dataProvider) > 0 ){
					
					// T'ableau d'affichage des données
		     		echo GridView::widget([
		     				'dataProvider' => new ArrayDataProvider([
		     						'allModels' => $dataProvider
		     				]),
		     				'columns' => $columns,
		     			]);
				}
			?>
		</div>	
	</div>
	<?php ActiveForm::end() ?>
	
</div>