<?php

use yii\helpers\Html;
use app\components\tocioRegles;

/* @var $this yii\web\View */
/* @var $model app\models\Cartes */

$this->title = 'Create Cartes';
$this->params['breadcrumbs'][] = ['label' => 'Cartes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cartes-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'cartesDefinition']); ?>

    <?= $this->render('_formCreate', [
        'model' => $model,
    ]) ?>

</div>
