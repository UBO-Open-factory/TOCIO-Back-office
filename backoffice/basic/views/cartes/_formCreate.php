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
foreach( method::find()->select(['nom_method','id'])->where(["id_carte" => $model->id])->indexBy('id')->column() as $id => $nature)
{
	$l_TAB_Options[] = '<option value="'. $id .'">' . $nature. '</option>';
}

?>


<div class="cartes-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>
    <div class="row">
	    <div class="col-sm-4">
	    	<?= $form->field($model, 'nom')->textarea(['rows' => 1]) ?>
		</div>
	    <div class="col-sm-12 ">
	    	<div class="pull-right">
	        	<?= Html::submitButton('Save', ['class' => 'btn btn-light']) ?>
	    	</div>
	    </div>
	</div>

    <?php ActiveForm::end(); ?>

</div>
