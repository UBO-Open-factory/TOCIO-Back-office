<?php

use yii\helpers\Html;
use app\components\tocioRegles;

/* @var $this yii\web\View */
/* @var $model app\models\Cartes */

$this->title = 'Update Cartes: ' . $model->id . " => " . $model->nom;
$this->params['breadcrumbs'][] = ['label' => 'Cartes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="cartes-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'cartesDefinition']); ?>

    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
