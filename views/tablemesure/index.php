<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\messageAlerte;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TablemesureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Tables des mesures";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablemesure-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Créer une Table de mesures', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo messageAlerte::widget(['type' => "secondary", "message" => "Afficher le nombre de données dans chacunes de stables."]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nom',
            'type',
            'tablename',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
