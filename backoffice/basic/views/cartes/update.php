<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\assets\CartesAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Cartes */

$this->title = 'Update Cartes: ' . $model->id . " => " . $model->nom;
$this->params['breadcrumbs'][] = ['label' => 'Cartes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

// Utilisation des ressources pour les cartes (JS + CSS)
CartesAsset::register($this);
?>
<div class="cartes-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'cartesDefinition']); ?>

    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
