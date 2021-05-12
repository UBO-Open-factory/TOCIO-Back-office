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

    <?php $form = ActiveForm::begin(['id'=>'test']);?>

    <div class="row">
        <div class="col-sm-3">
            <?php
            $list_nom = array_combine(Capteur::find()->select(['id'])->indexBy('id')->column(),Capteur::find()->select(['nom'])->indexBy('nom')->column());
            ?>
            <?= $form->field($model, 'id_capteur')->dropDownList($list_nom , ['class' => 'form-control id_capteur','id' => 'id_capteur','prompt'=>'Select ...'])?>
            <?= $form->field($model, 'nom_method')->dropDownList($method_pre["list"],['class' => 'form-control nom_method','id' => 'nom_method', 'onclick'=>'document.getElementById("sortie_method").value = $("#id_capteur option:selected").text() + "_" + $("#nom_method option:selected").text();','prompt'=>'Select ...']
            );
            ?>
            <?php
            echo $form->field($model, 'nom_method',['options' => ['class' => 'invisible']])->hiddenInput(['value'=> '','class' => 'form-control sortie_method','id' => 'sortie_method']);
            ?>
        </div>
        <div class="col-sm-6" style="margin-left:0%">
            <?= $form->field($model, 'method_include')->textarea(['rows' => 1 , 'value' => $method_pre['include'],'class' => 'form-control method_include ','id' => 'method_include']) ?>
            <?= $form->field($model, 'method_statement')->textarea(['rows' => 2 , 'value' => $method_pre['statement'],'class' => 'form-control','id' => 'method_statement']) ?>
            <?= $form->field($model, 'method_setup')->textarea(['rows' => 2 , 'value' => $method_pre['setup'],'class' => 'form-control','id' => 'method_setup']) ?>
        <label style="margin-left:1%"><h2> Instruction de lecture des grandeurs </h1></label>
            <?php
            $i = 0;
            $methode_lecture = explode(";",$method_pre['read']);
            $param_textbox = " spellcheck='false' style='resize:none;' ";
            foreach (relcapteurgrandeur::find()->where(["idCapteur" => 1])->all() 
            as $id_relgrandeur) 
            {                   
                foreach (grandeur::find()->where(["id" => $id_relgrandeur["idGrandeur"]])->all() 
                as $nom_grandeur) 
                {
                    echo "<label>" . $nom_grandeur['nature'] . "</label><br> ";
                    if(count($methode_lecture)-1>$i)
                    {
                        echo "<textarea class=\"read\" rows='2' cols='70'".$param_textbox.">".$methode_lecture[$i].";</textarea> ";
                    }
                    else
                    {
                        echo "<textarea class='read' rows='2' cols='70'".$param_textbox."></textarea> "; 
                    }
                    $i++;
                }
            }
            ?>
            <?php
            echo $form->field($model, 'method_read',['options' => ['class' => 'invisible']])->hiddenInput(['value'=> '','class' => 'sortie_read_method','id' => 'sortie_read_method',]);
            ?>
        </div>
            
    </div>
    <div class="form-group">
        <div class="col-sm-10"></div>
        <?= Html::submitButton('Save', ['class' => 'btn btn-info', 
        'onclick'=>
        'var elems = document.getElementsByClassName("read");
        for(var i=0; i<elems.length; i++) 
        {
            document.getElementById("sortie_read_method").value += elems[i].value;
        }'
        ]) ?>
    </div>

    	
    <?php ActiveForm::end(); ?>

</div>