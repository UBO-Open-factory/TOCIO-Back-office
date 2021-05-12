<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\relcartesmethod */

$this->title = 'Create Relcartesmethod';
$this->params['breadcrumbs'][] = ['label' => 'Relcartesmethods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relcartesmethod-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
