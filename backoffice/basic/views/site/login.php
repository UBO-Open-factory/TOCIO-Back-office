<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Authentification';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
    	'action' => "@urlbehindproxy/site/login",
        'id' => 'login-form',
        'fieldConfig' => [
            'template' => "<div class='row'><div class=\"col-lg-2\">{label}\n</div><div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-12\">{error}</div></div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>


	<?= Html::a('Mot de passe oublié ?', ['/"/utilisateur/pwdforgot"'], ['class'=>'link']) ?>
	<div class="form-group">
		<div class="col-lg-offset-1 col-lg-11">
			<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
		</div>
	</div>

    <?php ActiveForm::end(); ?>
</div>
