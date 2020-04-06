<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\messageAlerte;
use app\components\modulesWidget;
use Codeception\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modules';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="module-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<p>Un module est un ensemble de capteurs</p>
    <p>
        <?= Html::a('Nouveau Module', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= 
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
       		// ['class' => 'yii\grid\SerialColumn'], // Colonne série
			// 'id',
        	'nom',
            'identifiantReseau',
            'description:ntext',
        	'localisationModule.description',
        	['attribute' => 'actif',
       			'format' => 'html',
       			'value' => function($model){
        			return $model->actif == "1" ? "oui" : "non";
       			}
        	],
        	['attribute' => 'relmodulecapteur.idCapteur',
       			'format' => 'html',
       			'label' => "Capteurs attachés", 
       			'value' => function($model){
        			return implode('<br/>', ArrayHelper::map($model->idCapteurs, 'id', 'nom'));
       			}
        	],

        	['class' => 'yii\grid\ActionColumn', 
        		'visibleButtons' => ['view' => false,'update' => true, 'delete' => true]
        	],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>



<?php 
	echo modulesWidget::widget([
			'dataProvider' => $dataProvider,
	]);
?>