<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Grandeur */

$this->title = $model->tablename;
$this->params['breadcrumbs'][] = ['label' => 'Grandeurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="grandeur-view">

    <h1><?= $model->nature?> de la table <?= Html::encode($this->title) ?></h1>
    <?php 
     echo GridView::widget([
        'dataProvider' => $dataProvider,
     	'columns' => [ 	'id',
     					'timestamp:datetime',
	     				'valeur',
	     				'identifiantModule',
     					'posX', 
     					'posY', 
     					'posZ', 
	     			],
     	]);
     ?>
</div>
