<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Module;
use app\models\Capteur;
use yii\bootstrap\BaseHtml;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Relmodulecapteur */
/* @var $form yii\widgets\ActiveForm */

// Récupération du nom du capteur en foction de l'idModule
$request = Yii::$app->request;
$idModule	= $request->get('idModule');

$Module = Module::findOne(['identifiantReseau' => $idModule]);
?>

<div class="relmodulecapteur-form">

    <?php $form = ActiveForm::begin(['action' => Url::base().Url::to(), ]); ?>

	<?= $form->field($model, "idModule")->hiddenInput(['value'=> $idModule])?>
	<div class="row">
		<div class="col-sm-6">
			<?php echo $form->field($model, "idCapteur")->textInput(['disabled' => 'disabled','value'=> $Module->nom])->label("Module")?>
		</div>
		<div class="col-sm-6">
			<?php /* liste déroulante des capteurs*/
				echo $form->field($model, 'idCapteur')->dropDownList(
				Capteur::find()->select(['nom','id'])->indexBy('id')->column() );
		    ?>
		</div>
		<div class="col-sm-12">
			<?= $form->field($model, 'nomcapteur')->textarea(['rows' => 2])?>
		</div>

		<div class="col-sm-4">
			<?= $form->field($model, 'x')->textInput()->hint("Coordonnées X du capteur") ?>
		</div>

		<div class="col-sm-4">
			<?= $form->field($model, 'y')->textInput()->hint("Coordonnées Y du capteur")  ?>
		</div>

		<div class="col-sm-4">
			<?= $form->field($model, 'z')->textInput()->hint("Coordonnées Z du capteur")  ?>
		</div>
		<?= BaseHtml::activeHiddenInput($model, 'ordre', ['value' => 99]) ?>

		
    </div>
    <div class="row">
    	<div class="col-sm-1 offset-sm-11">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    	</div>
    </div>

    <?php ActiveForm::end(); ?>
</div>