<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\messageAlerte;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UtilisateursSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Utilisateurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="utilisateurs-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//             ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'email:email',
            'lastAccess:date',
       		['attribute' => 'NbDataTable',
       				'format' => 'html',
       				'label' => "Groupe",
       				'value' => function($model){
       					return $model->authAssignment->item_name;
       				}
       		],
//             'password:ntext',
//             'authKey',
            //'accessToken:ntext',

        		['class' => 'yii\grid\ActionColumn',
        				'visibleButtons' => [
        						'view' => false,
        						'update' => true,
        						'delete' => true,
        						],
        ],
        ],
    ] ); ?>
	<p>
		<?= Html::a(Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]). ' Créer un Utilisateur', ['create'], ['class' => 'btn btn-primary pull-right'])?>
	</p>
</div>
