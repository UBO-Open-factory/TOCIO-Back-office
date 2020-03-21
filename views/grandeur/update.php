<?php

use yii\helpers\Html;
use app\components\messageAlerte;

/* @var $this yii\web\View */
/* @var $model app\models\Grandeur */

$this->title = 'Mise à jour de la Grandeur: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grandeurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grandeur-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo messageAlerte::widget(['type' => "todo", "message" => "Faire cette partie."]); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
