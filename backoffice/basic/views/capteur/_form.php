<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Grandeur;

/* @var $this yii\web\View */
/* @var $model app\models\Capteur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="capteur-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="row">
		<div class="col-sm-4 bottom-align">
		    <?= $form->field($model, 'nom')->textarea(['rows' => 2]) ?>
		</div>
		<div class="col-sm-4 bottom-align">
			Afficher la liste déroulante des grandeurs ici
		    <? /* liste déroulante des Grandeurs*/
		    ?>
		</div>
	</div>
		
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
