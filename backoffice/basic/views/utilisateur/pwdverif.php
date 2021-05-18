<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Mot de passe oublié';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>


<?php $form = ActiveForm::begin( [
		'action' => "@urlbehindproxy/utilisateur/pwdupdate",
		'id' => 'forgot-form',
		'fieldConfig' => [
				'template' => "<div class='row'><div class=\"col-lg-2\">{label}\n</div><div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-12\">{error}</div></div>",
				'labelOptions' => ['class' => 'col-lg-2 control-label'],
		],
]); ?>

	<?= $form->field($model, 'id')->hiddenInput(['value'=> $model->id]) ?>
	<?= $form->field($model, 'username')->textInput(['readonly'=> true]) ?>
	<?= $form->field($model, 'email')->textInput(['readonly'=> true]) ?>
	<?= $form->field($model, 'password')->textInput(['autofocus' => true]) ?>

	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
			<?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'Submit']) ?>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
</div>
