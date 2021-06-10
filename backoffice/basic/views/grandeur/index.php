<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\Url;
use app\models\User;

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
        					return '<a href="'.Url::toRoute("/grandeur/graphique?id=".$model->id).'" title="Voir les données sous forme de graphique)"><i class="glyphicon glyphicon-stats"></i></a>';
				        		}
        	],
        	['attribute' => 'Detail',
        	'format' => 'html',
        	'label' => "",
        	'value' => function($model){
        	return '<a href="'.Url::toRoute("/grandeur/view?id=".$model->id."&sort=-timestamp").'" title="Voir les données sous forme de tableau)"><i class="glyphicon glyphicon-list"></i></a>';
				        			}
        	],
			['class' => 'yii\grid\ActionColumn',
        	'visibleButtons' => [
		      		'view' => false,
		        	'update' => false,
        			'delete' => function($model){
        							# if we got no value stored in the related table of data, we 
        							# display the delete button. 
			        				return $model->NbDataTable == 0;
			        			}],
        	],
        ],
    ]); ?>
<p>
		<?php
		if( Yii::$app->user->can('createGrandeur') ) {
			echo Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer une Grandeur', ['create'], ['class' => 'button buttonGrandeur pull-right']);
		}
		?>
	</p>
    <?php Pjax::end(); ?>

</div>
