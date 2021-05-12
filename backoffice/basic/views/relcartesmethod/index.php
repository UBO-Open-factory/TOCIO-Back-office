<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\relcartesmethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Relcartesmethods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relcartesmethod-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Relcartesmethod', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_carte',
            'id_method',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
