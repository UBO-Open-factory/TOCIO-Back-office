<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\messageAlerte;
use app\components\modulesWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\assets\ModuleAsset;
use app\components\capteursWidget;
use app\models\Capteur;
use yii\data\SqlDataProvider;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modules';
$this->params['breadcrumbs'][] = $this->title;

// Utilisaiton des ressources pour les modules (JS + CSS)
ModuleAsset::register($this);

?>

<div class="module-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<p>Un module est un ensemble de capteurs</p>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
//     	echo
//     GridView::widget([
//         'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
//         'columns' => [
//        		// ['class' => 'yii\grid\SerialColumn'], // Colonne série
// 			// 'id',
//         	'nom',
//             'identifiantReseau',
//             'description:ntext',
//         	'localisationModule.description',
//         	['attribute' => 'actif',
//        			'format' => 'html',
//        			'value' => function($model){
//         			return $model->actif == "1" ? "oui" : "non";
//        			}
//         	],
//         	['attribute' => 'relmodulecapteur.idCapteur',
//        			'format' => 'html',
//        			'label' => "Capteurs attachés", 
//        			'value' => function($model){
//         			return implode('<br/>', ArrayHelper::map($model->idCapteurs, 'id', 'nom'));
//        			}
//         	],

//         	['class' => 'yii\grid\ActionColumn', 
//         		'visibleButtons' => ['view' => false,'update' => true, 'delete' => true]
//         	],
//         ],
//     ]); 
// 	?>


    <div class="row">
    	<div class="col-sm-9">
			<?php
				echo yii\jui\Draggable::widget();
				echo modulesWidget::widget(['dataProvider' => $dataProvider,]);
				echo Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer un Module', ['create'], ['class' => 'btn btn-success pull-right']);
			?>
    	</div>
    	<div class="col-sm-3" id="listeCapteursFix">
    		<div id="listeCapteursFix-Content">
	    		<h3>Capteurs disponibles</h3>
		    	<?php 
		    		$capteurProvider = new SqlDataProvider([
		    				'sql' 	=> "SELECT * FROM capteur"
		    		]);
	
					echo capteursWidget::widget(['dataProvider' => $capteurProvider,]);
					echo Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer un Capteur', ['capteur/create'], ['class' => 'btn btn-info pull-right']);
		    	?>
    		</div>
    	</div>
    </div>
	

    <?php Pjax::end(); ?>
</div>

<?php /*@todo  Pouvoir filtrer l'affichage sur le nom du module*/
echo messageAlerte::widget(['type' => "todo", "message" => "Pouvoir filtrer l'affichage sur le nom du module"]); ?>
