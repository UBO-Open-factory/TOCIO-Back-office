<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\components\messageAlerte;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = "Création d'un Module";
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle'=>'moduleDefinition']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
