<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\messageAlerte;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ModuleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modules';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<p> Un module est un ensemble de capteurs</p>
    <p>
        <?= Html::a('Nouveau Module', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nom',
            'idCapteur:ntext',
            'identifiantReseau',
            'description:ntext',
            'idLocalisationModule',
            //'positionCapteur:ntext',
            //'actif',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php /*@todo  Afficher la localisation en toutes lettre plutôt que l'ID*/
echo messageAlerte::widget(['type' => "todo", "message" => "Afficher la localisation en toutes lettre plutôt que l'ID"]); 
?>
<?php /*@todo  Afficher le nom des catpeurs plutôt que leur ID*/
echo messageAlerte::widget(['type' => "todo", "message" => "Afficher le nom des catpeurs plutôt que leur ID"]); ?>

<?php /*@todo  Supprimer la colonne CapteurID*/
echo messageAlerte::widget(['type' => "todo", "message" => "Supprimer la colonne CapteurID"]); ?>