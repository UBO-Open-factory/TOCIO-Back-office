<?php
/**
 * @todo : Pouvoir créer dynamiquement une table pour stocker les valeur des capteurs.
 */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\tocioRegles;
use app\components\messageAlerte;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GrandeurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grandeurs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grandeur-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?= tocioRegles::widget(["regle" => "grandeurDefinition"])?>

    <p>
        <?= Html::a('Nouvelle Grandeur', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nature',
            'formatCapteur',
            'tablename',
            'type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
