<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tablemesure */

$this->title = "Mise à jour de la Table des mesures: " . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tablemesures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tablemesure-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
