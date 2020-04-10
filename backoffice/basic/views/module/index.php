<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\messageAlerte;
use app\components\modulesWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\assets\ModuleAsset;
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


	<?php 
		echo modulesWidget::widget([
				'dataProvider' => $dataProvider,
		]);
	?>
	
	<p>
	<?= Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer un module', ['create'], ['class' => 'btn btn-success pull-right'])?>
	</p>
    <?php Pjax::end(); ?>
</div>

<?php /*@todo  Faire le distingo pour les module LORA (dans l'affichage de la trame attendu)*/
echo messageAlerte::widget(['type' => "todo", "message" => "Faire le distingo pour les module LORA (dans l'affichage de la trame attendu)"]); ?>
<?php /*@todo  Pouvori faire du drag'n drop dans l'ordre des capteurs d'un module*/
echo messageAlerte::widget(['type' => "todo", "message" => "Pouvoir faire du drag'n drop dans l'ordre des capteurs d'un module"]); ?>
<?php /*@todo  Rajouter le nom du capteur dans la légende de la payload*/
echo messageAlerte::widget(['type' => "todo", "message" => "Rajouter le nom du capteur dans la légende de la payload"]); ?>