<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RelmodulecapteurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Relmodulecapteurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relmodulecapteur-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a("Création d'une relation module/capteur", ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'idModule',
            'idCapteur',
            'nomcapteur:ntext',
            'x',
            'y',
            'z',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
