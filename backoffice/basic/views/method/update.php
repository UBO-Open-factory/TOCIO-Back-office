<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\assets\MethodUpdateAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Method */

$this->title = 'Update Method: ' . $model->id . " => " . $model->nom_method;
$this->params['breadcrumbs'][] = ['label' => 'Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

MethodUpdateAsset::register($this);
?>
<div class="method-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'methodDefinition']); ?>

    <?= $this->render('_formUpdate', ['model' => $model, 'method_pre' => $method_pre]) ?>

</div>