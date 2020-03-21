<?php

use yii\helpers\Html;
use app\components\tocioRegles;

/* @var $this yii\web\View */
/* @var $model app\models\Tablemesure */

$this->title = "Création d'un Table des mesures";
$this->params['breadcrumbs'][] = ['label' => 'Tablemesures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablemesure-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'tablemesuredefinition']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
