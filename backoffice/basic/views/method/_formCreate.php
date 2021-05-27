<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\capteur;
use app\models\Cartes;
use app\models\Method;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\models\relcapteurgrandeur;
use app\models\grandeur;
/* @var $this yii\web\View */
/* @var $model app\models\Method */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="method-form">

    <?php $form = ActiveForm::begin();?>

    <div class="row">
        <div class="col-sm-3">
            <?php
            $list_nom = array_combine(Capteur::find()->select(['id'])->indexBy('id')->column(),Capteur::find()->select(['nom'])->indexBy('nom')->column());
            ?>
            <?= $form->field($model, 'id_capteur')->dropDownList($list_nom , ['class' => 'form-control id_capteur','id' => 'id_capteur','prompt'=>'Select ...'])?>
            <?= $form->field($model, 'nom_method')->dropDownList($method_pre["list"],['class' => 'form-control nom_method','id' => 'nom_method','prompt'=>'Select ...']);
            ?>
            <?php
            echo $form->field($model, 'nom_method',['options' => ['class' => 'invisible']])->hiddenInput(['value'=> '','class' => 'form-control sortie_method','id' => 'sortie_method']);
            ?>
        </div>
        <div class="col-sm-6" style="margin-left:30%">
            <?= $form->field($model, 'method_include')->textarea(['rows' => 1 , 'value' => $method_pre['include'],'class' => 'form-control method_include ','id' => 'method_include']) ?>
            <?= $form->field($model, 'method_statement')->textarea(['rows' => 2 , 'value' => $method_pre['statement'],'class' => 'form-control','id' => 'method_statement']) ?>
            <?= $form->field($model, 'method_setup')->textarea(['rows' => 2 , 'value' => $method_pre['setup'],'class' => 'form-control','id' => 'method_setup']) ?>
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
        	<div id = "FullMenu" style ="height: <?php echo $taillemax*100; ?>px;">
	            <?php
	            $param_textbox = " spellcheck='false' style='resize:none;' ";
	            foreach(capteur::find()->all() as $capteurid)
	            {
	            	echo "<div class='grandeurTextBox ". $capteurid['nom'] ."' style='position: absolute;'>"; 
		            foreach (relcapteurgrandeur::find()->where(["idCapteur" => $capteurid['id']])->all() as $id_relgrandeur)
		            {        
		                foreach (grandeur::find()->where(["id" => $id_relgrandeur["idGrandeur"]])->all() as $nom_grandeur) 
		                {
		                	echo "<label class='grandeurTextBox ". $capteurid['nom'] ."' id='". $capteurid['nom'] . "' >" . $nom_grandeur['nature'] . "</label><br>";
		                	echo "<textarea class='grandeurTextBox  ". $capteurid['nom'] ." ". $capteurid['id'] ."' id='". $capteurid['nom'] ."' rows='2' cols='70'".$param_textbox."></textarea> "; 
		                }
		                
		            }
		            echo "</div>";
		        }
	            ?>
	            <?php
	            echo $form->field($model, 'method_read',['options' => ['class' => 'invisible']])->hiddenInput(['value'=> '','class' => 'sortie_read_method','id' => 'sortie_read_method',]);
	            ?>


	        </div>
	        <div class="form-group">
		        <div class="col-sm-10"></div>
		        <?= Html::submitButton('Save', ['class' => 'btn btn-secondary','id' => 'methodsubmitbutton' ]) ?>
		    </div>
        </div>
            
    </div>
    

    	
    <?php ActiveForm::end(); ?>

</div>