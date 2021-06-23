<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\tocioRegles;

$this->title = 'Import de journaux de Mesure au format CSV';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablemesure-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<p>	<?php echo tocioRegles::widget(['regle' => 'fichiercsv'])?></p>

	
	<?php
		// On a eu des erreurs lors de l'import des lignes du fichier CSV  
		if (is_array($model->ErrorMessages) ){
			echo "<div class='error'><h2>Rejet des lignes :</h2>";
			echo "<ul>";
			foreach ($model->ErrorMessages as $error){
				echo "<li><b>".$error['error']."</b> : ".$error['message']."</li>";
			}
			echo "</ul></div>";
		} 
	?>
	

	<?php $form = ActiveForm::begin(
                        ['action' => ['/site/upload'],
                         'options' => ['enctype' => 'multipart/form-data']]
                        );      ?>
	<div class="row">
		<div class="col">
			<?= /* Liste déroulante des Modules */
			$form->field( $model, 'moduleID' )->dropDownList([$listModules]);
			?>
		</div>
		<div class="col">
			<?= $form->field($model, 'CSVFile')->fileInput(['multiple' => false]) ?>
		</div>			
		<div class="col">
			<br/>
			<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'Upload']) ?>
		</div>
	</div>
		
   
	<?php ActiveForm::end() ?>

</div>
