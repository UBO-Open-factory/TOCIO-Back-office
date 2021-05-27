<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\ArrayHelper;
use app\models\Position;
use app\components\methodWidget;
use yii\helpers\Url;
use app\assets\MethodIndexAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Method';
$this->params['breadcrumbs'][] = $this->title;

MethodIndexAsset::register($this);
?>

<div class="method-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'methodDefinition']); ?>
    <p>
        <?= Html::a("Créer une méthode", ['create'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?php
        echo methodWidget::widget(['dataProvider' => $dataProvider,]);
    ?>
    <p style="text-align:right">
        <?= Html::a("Créer une méthode", ['create'], ['class' => 'btn btn-secondary']) ?>
    </p>
</div>
