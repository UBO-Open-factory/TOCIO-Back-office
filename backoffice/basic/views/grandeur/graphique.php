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
    // Dans la table des grandeurs, on récupère les ID des modules
    $modulesID = (new \yii\db\Query())
			    ->select(['identifiantModule'])
			    ->from($model->tablename)
			    ->groupBy(['identifiantModule'])
			    ->all();
    // Récupération des valeurs du module
	$mesures = [];
    foreach( $modulesID as $moduleID){
    	$id = $moduleID['identifiantModule'];
    	$mesures[$id]	= (new \yii\db\Query())
					    	->select(['timestamp', 'valeur'])
					    	->from($model->tablename)
					    	->where(['identifiantModule' => $id])
					    	->all();
    }
    
    // Formattage des series
    /* Elles doivent être de la forme :
     * [['name' => 'Entity 1',
    		'data' => [['2018-10-04', 4.66],
    					['2018-10-05', 5.0],
    				],
   		],];
     */
    $series = [];
    foreach( $mesures as $moduleID => $valeurs){
    	$data = [];
    	foreach( $valeurs as $valeur){
   			$data[] = [$valeur['timestamp'], $valeur['valeur']];
    	}
    	
    	$series[] = ['name' => $moduleID, 
    				'data'	=> $data];
    }

    
    // @see https://apexcharts.com/docs/legend/
    echo \onmotion\apexcharts\ApexchartsWidget::widget([
    		'type' => 'area', // default area
    		'height' => '400', // default 350
    		'width' => '100%', // default 100%
    		'chartOptions' => [
    				'chart' => [
    						'toolbar' => [
    								'show' => true,
    								'autoSelected' => 'zoom'
    						],
    				],
    				'xaxis' => [
    						'type' => 'datetime',
    						// 'categories' => $categories,
    				],

    				'dataLabels' => [
    						'enabled' => False,
    				],
    				'stroke' => [
    						'curve' => 'smooth',
    				],
    				'legend' => [
    						'verticalAlign' => 'bottom',
    						'horizontalAlign' => 'left',
    						'style' => ['colors' => ['#FFFFFF', '#E91E63', '#9C27B0']],
    				],
    				'fill' => [
    					'type' => 'gradient',
    					'gradient' => [
    						'shadeIntensity' =>  1,
    						'opacityFrom' =>  0.7,
    						'opacityTo' =>  0.5,
    						'stops' =>  [0, 90, 100],
    					]
    				],
    		],
    		'series' => $series
    ]);
    ?>
</div>
