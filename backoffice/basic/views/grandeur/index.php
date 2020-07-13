<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\Url;

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
        				'label' => "Nb données stockées",
        				'value' => function($model){
				        				return $model->NbDataTable;
				        			}
        	],
        	['attribute' => 'Graphique',
        				'format' => 'html',
        				'label' => "",
        				'value' => function($model){
        				return "<a href=".Url::toRoute("/grandeur/graphique?id=".$model->id)."><i class='glyphicon glyphicon-random'></i></a>";
				        			}
        	],
			['class' => 'yii\grid\ActionColumn',
        	'visibleButtons' => [
					        	'view' => true,
					        	'update' => false,
					        	'delete' => false,
        						],
        	],
        ],
    ]); ?>
<p>
		<?= Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer une Grandeur', ['create'], ['class' => 'btn btn-primary pull-right'])?>
	</p>
    <?php Pjax::end(); ?>

</div>
