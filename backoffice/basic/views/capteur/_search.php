<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CapteurSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
		'action' => Url::to(['index']),
        'method' => 'get',
]); ?>
<div class="capteur-search">
<div class="row">
	<div class="col-md-8">
		
	    <?php // echo $form->field($model, 'id') ?>
		<!-- Search box -->	
	    <?= $form->field($model, 'nom') ?>
	</div>
	
	<div class="col-md-4">
	    <div class="form-group">
	    	<label class="control-label">&nbsp;</label><br/>
	        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
	        <?php // echo Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
	    </div>
	</div>	
</div>
</div>
<?php ActiveForm::end(); ?>