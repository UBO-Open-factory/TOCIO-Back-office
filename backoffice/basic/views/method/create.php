<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\assets\MethodCreateAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Method */

$this->title = "Création d'une méthode";
$this->params['breadcrumbs'][] = ['label' => 'Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

MethodCreateAsset::register($this);
?>
<div class="method-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'methodDefinition']); ?>

    <?= $this->render('_formCreate', ['model' => $model, 'method_pre' => $method_pre]) ?>

</div>
