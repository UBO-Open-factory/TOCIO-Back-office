<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\components\messageAlerte;
use yii\helpers\ArrayHelper;
use yii\grid\ActionColumn;
use app\components\tocioRegles;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LocalisationmoduleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Localisation de modules';
$this->params['breadcrumbs'][] = $this->title;

// Enregistrement de L'URl pour le retour avec Url::previous()
Url::remember();
?>
<div class="localisationmodule-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>	<?php echo tocioRegles::widget(['regle' => 'localisationmodule'])?></p>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'description:ntext',
            'coordX',
            'coordY',
            'coordZ',
			['attribute' => 'relmodulecapteur.idCapteur',
				'format' => 'html',
                'label' => "Module rattachées",
                'value' => function($model){
                			return implode(',<br/> ', ArrayHelper::map($model->modules, 'identifiantReseau', 'nom'));
                }
			],
			['class' => 'yii\grid\ActionColumn',
					'visibleButtons' => [
							'view' => false,
							'update' => true, 
							'delete' => function ($model, $key, $index) {
											return $model->modules == null;
							}]
			],
    		
        ],
    ]); ?>
    
	<p>
		<?= Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer une Localisation', ['create'], ['class' => 'btn btn-primary pull-right'])?>
	</p>
    <?php Pjax::end(); ?>

</div>
