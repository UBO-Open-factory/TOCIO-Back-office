<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\relcartesmethod */

$this->title = 'Update Relcartesmethod: ' . $model->id_carte;
$this->params['breadcrumbs'][] = ['label' => 'Relcartesmethods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_carte, 'url' => ['view', 'id_carte' => $model->id_carte, 'id_method' => $model->id_method]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="relcartesmethod-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
