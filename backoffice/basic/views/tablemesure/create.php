<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tablemesure */

$this->title = 'Create Tablemesure';
$this->params['breadcrumbs'][] = ['label' => 'Tablemesures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablemesure-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
