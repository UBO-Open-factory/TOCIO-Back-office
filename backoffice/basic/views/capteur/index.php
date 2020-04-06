<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\ArrayHelper;
use app\models\Position;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CapteurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Capteurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capteur-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= tocioRegles::widget(['regle' => "capteurDefinition"])?>
    
    <p>
        <?= Html::a('Nouveau Capteur', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nom:ntext',
        	['attribute' => 'relmodulecapteur.idCapteur',
        	'format' => 'html',
        	'label' => "Capteurs rattachés",
        	'value' => function($model){
        					return implode(',<br/> ', ArrayHelper::map($model->idGrandeurs, 'id', 'nature'));
        				}
        	],

        	['class' => 'yii\grid\ActionColumn',
        			'visibleButtons' => ['view' => false,'update' => true, 'delete' => true]
        	],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
