<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\ArrayHelper;
use app\models\Position;
use app\components\capteursWidget;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CapteurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Capteurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capteur-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= tocioRegles::widget(['regle' => "capteurDefinition"])?>
    

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
//     GridView::widget([
//         'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
//         'columns' => [
//             // ['class' => 'yii\grid\SerialColumn'],

//             'id',
//             'nom:ntext',
//         	['attribute' => 'relmodulecapteur.idCapteur',
//         	'format' => 'html',
//         	'label' => "Grandeurs rattachées",
//         	'value' => function($model){
//         					return implode(',<br/> ', ArrayHelper::map($model->idGrandeurs, 'id', 'nature'));
//         	],

//         	['class' => 'yii\grid\ActionColumn',
//         			'visibleButtons' => ['view' => false,'update' => true, 'delete' => true]
//         	],
//         ],
//     ]); 
?>
    
    	<?php
		echo capteursWidget::widget([
				'dataProvider' => $dataProvider,
		]);
	?>
	
	<p>
		<?= Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer un Capteur', ['create'], ['class' => 'btn btn-info pull-right'])?>
	</p>
    <?php Pjax::end(); ?>
</div>
