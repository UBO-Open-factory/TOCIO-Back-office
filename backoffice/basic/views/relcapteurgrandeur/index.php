<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RelcapteurgrandeurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Relcapteurgrandeurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relcapteurgrandeur-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Relcapteurgrandeur', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idCapteur',
            'idGrandeur',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
