<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Relcartesmethod;
use app\models\Method;
use app\models\Capteur;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Cartes */
/* @var $form yii\widgets\ActiveForm */

$l_TAB_MethodAssociees = relcartesmethod::find()->select(['id_method'])->where(["id_carte" => $model->id])->column();

$l_TAB_Options = [];
foreach( method::find()->select(['nom_method','id'])->indexBy('id')->column() as $id => $nature){
	// si la grandeur n'est pas déjà associée à cette carte, on la met dans la liste
	if( !in_array($id, $l_TAB_MethodAssociees)) {
		$l_TAB_Options[] = '<option value="'. $id .'">' . $nature. '</option>';
	}
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
