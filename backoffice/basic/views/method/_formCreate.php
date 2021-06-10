<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Capteur;
use app\models\Cartes;
use app\models\Method;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\Relcapteurgrandeur;
use app\models\Grandeur;
/* @var $this yii\web\View */
/* @var $model app\models\Method */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="method-form">

    <?php $form = ActiveForm::begin();?>
    <?php Url::remember(); ?>
    <div class="row">
        <div class="col-sm-3">
            <?php
            $list_nom = array_combine(Capteur::find()->select(['id'])->indexBy('id')->column(),Capteur::find()->select(['nom'])->indexBy('nom')->column());
            ?>
            <?php echo Html::activeLabel($model,'id_carte');?>
            <div class="input-group ">
                <?= $form->field($model, 'id_carte')->dropDownList($method_pre["list"],['class' => 'form-control nom_method ','id' => 'nom_method','prompt'=>'Select ...'])->label(false);?>
                <div>
                    <?= Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus "]), ['cartes/create'], ['class' => 'button buttonCarte']);?>
                </div>
            </div>
            <div id="liste_capteur">
                <?= $form->field($model, 'id_capteur')->dropDownList($list_nom , ['class' => 'form-control id_capteur','id' => 'id_capteur','prompt'=>'Select ...'])?>
            </div>

            <!-- Hidden textarea for nom method , value set by JS when save button is clicked-->
            <?php echo $form->field($model, 'nom_method',['options' => ['class' => 'invisible']])->hiddenInput(['value'=> '','class' => 'form-control sortie_method','id' => 'sortie_method']); ?>
        </div>
        <div class="col-sm-6" >
            <!-- Formulaire group, set to be hidde of display -->
            <div id="groupe_formulaire">
                <!-- Input textarea for the 3 first colonn -->
                <?= $form->field($model, 'method_include')->textarea(['rows' => 1 , 'value' => $method_pre['include'],'class' => 'form-control','id' => 'method_include','title' => "#include <youLibrarie>"]) ?>
                <?= $form->field($model, 'method_statement')->textarea(['rows' => 2 , 'value' => $method_pre['statement'],'class' => 'form-control','id' => 'method_statement','title' => "declare your sensor : {{sensorName}} {{sensorPin}} "]) ?>
                <?= $form->field($model, 'method_setup')->textarea(['rows' => 2 , 'value' => $method_pre['setup'],'class' => 'form-control','id' => 'method_setup','title' => "init your sensor : {{sensorName}} .begin() .start()"]) ?>
            	<label style="margin-left:1%"><h2> Instruction de lecture des grandeurs </h2></label>
            	<?php
            	$taillemax = 0;
            	$taillesave = 0;
            	foreach(capteur::find()->all() as $taillecapteur)
            	{
            		foreach(relcapteurgrandeur::find()->where(["idCapteur" => $taillecapteur['id']])->all() as $taillegrandeur)
            		{
            			$taillesave++;
            		}
            		if($taillesave > $taillemax)
            		{
            			$taillemax = $taillesave;
            		}
            		$taillesave = 0;
            	}
            	?>
            	<div id = "FullMenu">
    	            <div id = "DisplayBalise"></div>
    	            <?php
    	            echo $form->field($model, 'method_read',['options' => ['class' => 'invisible']])->hiddenInput(['value'=> '','class' => 'sortie_read_method','id' => 'sortie_read_method',]);
    	            ?>
    	        </div>
    	        <div class="form-group">
    		        <?= Html::submitButton('Enregistrer', ['class' => 'button buttonMethod pull-right','id' => 'methodsubmitbutton' ]) ?>
    		    </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="button_balise">
                <label> Ajouter des balises </label><br>
                <?= Html::Button('sensorName', ['class' => 'button buttonBalise','id' => 'variable' ]) ?>
                <?= Html::Button('sensorPin', ['class' => 'button buttonBalise','id' => 'pin' ]) ?>
                <br>
                Ces balises permettent au générateur de code de placer les variables et les pins dans le programme.
            </div>
        </div>
            
    </div>
    

    	
    <?php ActiveForm::end(); ?>

</div>
