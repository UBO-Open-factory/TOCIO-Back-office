<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LocalisationmoduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localisation de modules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="localisationmodule-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nouvelle localisation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'description:ntext',
            'coordX',
            'coordY',
            'coordZ',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
