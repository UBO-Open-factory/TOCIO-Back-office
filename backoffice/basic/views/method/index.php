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
/* @var $this yii\web\View */
/* @var $searchModel app\models\MethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Methodes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="method-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'methodDefinition']); ?>
    <p>
        <?= Html::a('Create Method', ['create'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
        echo methodWidget::widget(['dataProvider' => $dataProvider,]);
    ?>
    <p style="text-align:right">
        <?= Html::a('Create Method', ['create'], ['class' => 'btn btn-info']) ?>
    </p>
</div>
