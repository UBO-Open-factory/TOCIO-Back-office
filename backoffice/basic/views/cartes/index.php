<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\tocioRegles;
use app\components\cartesWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CartesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cartes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cartes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'cartesDefinition']); ?>
    <p>
        <?= Html::a('Create Cartes', ['create'], ['class' => 'btn btn-light']) ?>
    </p>

    <?php
        echo cartesWidget::widget(['dataProvider' => $dataProvider,]);
    ?>

    <p style="text-align:right">
        <?= Html::a('Create Cartes', ['create'], ['class' => 'btn btn-light']) ?>
    </p>

</div>
