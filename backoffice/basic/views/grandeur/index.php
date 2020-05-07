<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\tocioRegles;
use app\components\messageAlerte;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GrandeurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grandeurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grandeur-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?= tocioRegles::widget(["regle" => "grandeurDefinition"])?>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'columns' => [
//             ['class' => 'yii\grid\SerialColumn'],
//             'id',
            'nature',
            'formatCapteur',
            'type',
            'tablename',
        	['attribute' => 'NbDataTable',
        				'format' => 'html',
        				'label' => "Nb Data stockées",
        				'value' => function($model){
        				return $model->NbDataTable;
        				}
        	],
        	['class' => 'yii\grid\ActionColumn',
        				'visibleButtons' => [
        						'view' => false,
        						'update' => false,
        						'delete' => function ($model, $key, $index) {
        						return $model->NbDataTable == 0;
        						}]
        						],
    		
        ],
    ]); ?>
<p>
		<?= Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer une Grandeur', ['create'], ['class' => 'btn btn-primary pull-right'])?>
	</p>
    <?php Pjax::end(); ?>

</div>
