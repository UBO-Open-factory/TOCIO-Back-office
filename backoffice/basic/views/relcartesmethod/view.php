<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\relcartesmethod */

$this->title = $model->id_carte;
$this->params['breadcrumbs'][] = ['label' => 'Relcartesmethods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="relcartesmethod-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_carte' => $model->id_carte, 'id_method' => $model->id_method], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_carte' => $model->id_carte, 'id_method' => $model->id_method], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_carte',
            'id_method',
        ],
    ]) ?>

</div>
