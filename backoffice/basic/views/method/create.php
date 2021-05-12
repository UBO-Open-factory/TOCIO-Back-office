<?php

use yii\helpers\Html;
use app\components\tocioRegles;

/* @var $this yii\web\View */
/* @var $model app\models\Method */

$this->title = 'Create Methode';
$this->params['breadcrumbs'][] = ['label' => 'Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="method-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'methodDefinition']); ?>

    <?= $this->render('_formCreate', ['model' => $model, 'method_pre' => $method_pre]) ?>

</div>
