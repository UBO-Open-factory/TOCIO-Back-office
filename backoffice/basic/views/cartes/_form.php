<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Cartes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cartes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nom')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'button buttonCarte']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
