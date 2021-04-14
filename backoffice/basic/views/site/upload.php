<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\tocioRegles;

$this->title = 'Import de journaux de Mesure au format CSV';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablemesure-index">

	<h1><?= Html::encode($this->title) ?></h1>
	
	<?php
		// On a eu des erreurs lors de l'import des lignes du fichier CSV  
		if (is_array($model->ErrorMessages) ){
			echo "<h2>Rejet des lignes :</h2>";
			echo "<ul><li>".implode("</li><li>",$model->ErrorMessages)."</li></ul>";
		} 
	?>
	
	<p>	<?php echo tocioRegles::widget(['regle' => 'fichiercsv'])?></p>

	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])?>
	<?= $form->field($model, 'CSVFile')->fileInput(['multiple' => false]) ?>
	
	<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'Upload']) ?>
   
	<?php ActiveForm::end() ?>

</div>