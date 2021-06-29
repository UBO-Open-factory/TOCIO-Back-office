<?php

use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Logs des imports automatiques';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//             ['class' => 'yii\grid\SerialColumn'],

            //'id',
//             'url',
//             'name',
            'date:datetime',
        	[
        		'label' => 'Type',
        		'format' => 'raw',
        		'value' => function ($data) {
        			return Html::a(Html::encode($data['type']),$data['url']);
        		},
        	],
        	[
        		'label' => 'File name',
        		'format' => 'raw',
        		'value' => function ($data) {
        			return Html::a(Html::encode($data['name']),$data['url']);
        		},
        	],
        	[
        		'label' => 'Link',
        		'format' => 'raw',
        		'value' => function ($data) {
        			return Html::a(Html::encode("View"),$data['url']);
        		},
        	],

//         		['class' => 'yii\grid\ActionColumn',
//         		'visibleButtons' => ['view' => true,'update' => false, 'delete' => false]
//         		],
        ],
    ]); ?>


</div>
