<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\UtilisateursGroup;
use app\models\UtilisateurGroup;

/* @var $this yii\web\View */
/* @var $model app\models\Utilisateurs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="utilisateurs-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>
    <div class="row">
		<div class="col-sm-4">
		    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-sm-4">
			<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
		</div>
		<div class="col-sm-4">
			<?= $form->field($model, 'password')->textarea(['rows' => 1]) ?>
		</div>
		<div class="col-sm-4">
			<?= $form->field($model, 'idGroupe')->label('Groupe')
				->dropDownList(
					UtilisateurGroup::find()->select(['groupName', 'id'])->indexBy('id')->column(),
														['prompt'=>'Choisir un Groupe pour cet utilisateur'])
					
			?>
		</div>
	</div>
    <div class="form-group">
        <?= Html::submitButton('Enregistrer', ['class' => 'btn btn-primary pull-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
