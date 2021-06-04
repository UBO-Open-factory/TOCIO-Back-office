<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\capteur;
use app\models\Cartes;
use app\models\Method;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Method */
/* @var $form yii\widgets\ActiveForm */

$l_TAB_ListeNomCapteur = array_combine(Capteur::find()->select(['id'])->indexBy('id')->column(),Capteur::find()->select(['nom'])->indexBy('nom')->column());
?>

<div class="method-form">

    <?php 
    $form = ActiveForm::begin();
    ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'id_capteur')->dropDownList($l_TAB_ListeNomCapteur)?>
        </div>
        <div class="col-sm-12">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'method_include')->textarea(['rows' => 1 , 'value' => $method_pre['include']]) ?>
                <?= $form->field($model, 'method_statement')->textarea(['rows' => 2 , 'value' => $method_pre['statement']]) ?>
                <?= $form->field($model, 'method_setup')->textarea(['rows' => 2 , 'value' => $method_pre['setup']]) ?>
                <?= $form->field($model, 'method_read')->textarea(['rows' => 6 , 'value' => $method_pre['read']]) ?>
                <script>
                    var editor = CodeMirror.fromTextArea(document.getElementById("articles-full_text"), 
                    {
                        lineNumbers: true,
                        lineWrapping: true,
                        matchBrackets: true,
                        mode: "application/x-httpd-php",
                        indentUnit: 2,
                        indentWithTabs: true,
                        tabMode: "indent"
                    });
                </script>
            </div>
        </div>
        <div class="col-sm-6">
        </div>
        <div class="col-sm-3">
            <?php
            //$form->field($model, "nom_method")->hiddenInput(['value'=> 'test'])
            echo $form->field($model, 'nom_method')->dropDownList($method_pre["list"],['prompt'=>'Choose...']);
            ?>
        </div>
    </div>


    <div class="form-group">
        <div class="col-sm-10"></div>
        <?= Html::submitButton('Save', ['class' => 'button buttonMethod']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>