<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Relcapteurgrandeur */

$this->title = 'Update Relcapteurgrandeur: ' . $model->idCapteur;
$this->params['breadcrumbs'][] = ['label' => 'Relcapteurgrandeurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idCapteur, 'url' => ['view', 'idCapteur' => $model->idCapteur, 'idGrandeur' => $model->idGrandeur]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="relcapteurgrandeur-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
