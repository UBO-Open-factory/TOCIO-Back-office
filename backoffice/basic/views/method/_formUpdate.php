<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\capteur;
use app\models\Cartes;
use app\models\Method;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\models\Relcapteurgrandeur;
use app\models\Grandeur;
/* @var $this yii\web\View */
/* @var $model app\models\Method */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="method-form">

    <?php $form = ActiveForm::begin();?>

    <div class="row">
    	<div class="col-sm-3">
    	</div>
		<div class="col-sm-6">
                <?= $form->field($model, 'method_include')->textarea(['rows' => 1 , 'value' => $method_pre['include'],'class' => 'form-control method_include ','id' => 'method_include']) ?>
                <?= $form->field($model, 'method_statement')->textarea(['rows' => 2 , 'value' => $method_pre['statement'],'class' => 'form-control','id' => 'method_statement']) ?>
                <?= $form->field($model, 'method_setup')->textarea(['rows' => 2 , 'value' => $method_pre['setup'],'class' => 'form-control','id' => 'method_setup']) ?>
        <label style="margin-left: 1%"><h2> Instruction de lecture des grandeurs </h1></label>
            <?php
				$i = 0;
				$methode_lecture = explode( "|CutBalise|", $method_pre['read'] );
				$param_textbox = " spellcheck='false' style='resize:none;' ";
				foreach( relcapteurgrandeur::find()->where( ["idCapteur" => $method_pre['id_capteur'] ] )->all() as $id_relgrandeur ) 
				{
					foreach( grandeur::find()->where( ["id" => $id_relgrandeur["idGrandeur"] ] )->all() as $nom_grandeur ) 
					{
						echo "<label>".$nom_grandeur['nature']."</label><br> ";
						if( count( $methode_lecture ) - 1 > $i ) 
						{
							echo "<textarea class='".capteur::find()->where( [
									"id" => $method_pre['id_capteur'] ] )->one()["nom"]." grandeurTextBox' rows='2' cols='64'".$param_textbox.">".$methode_lecture[$i]."</textarea> ";
						} 
						else 
						{
							echo "<textarea class='".capteur::find()->where( [
									"id" => $method_pre['id_capteur'] ] )->one()["nom"]." grandeurTextBox' rows='2' cols='64'".$param_textbox."></textarea> ";
						}
						$i++;
					}
				}
				?>
        
        <?= $form->field( $model, 'method_read', [
					'options' => ['class' => 'invisible' ] ] )->hiddenInput( [
														'value' => '',
														'class' => 'sortie_read_method',
														'id' => 'sortie_read_method' ] );
			?>
		</div>
		<div class="col-sm-3">
            <label> Ajouter des balises </label><br>
            <?= Html::Button('sensorName', ['class' => 'button buttonBalise','id' => 'variable' ]) ?>
            <?= Html::Button('sensorPin', ['class' => 'button buttonBalise','id' => 'pin' ]) ?><br>
            Ces balises permettent au générateur de code de placer les variables et les pins dans le programme.
        </div>
    </div>
	<div class="form-group">
		<div class="col-sm-10"></div>
        <?=Html::submitButton( 'Enregistrer', ['class' => 'button buttonMethod','id' => 'methodsubmitbutton' ] )?>
    </div>
    <?php ActiveForm::end(); ?>

</div>