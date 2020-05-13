<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TablemesureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tablemesures';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablemesure-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tablemesure', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'timestamp',
            'valeur',
            'posX',
            'posY',
            //'posZ',
            //'identifiantModule',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
