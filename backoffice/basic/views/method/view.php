<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Method */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="method-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-secondary',
            'data' => [
                'confirm' => 'Are you sure you want to delete this itemzefzfe?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_capteur',
            'nom_method:ntext',
            'method_include:ntext',
            'method_statement:ntext',
            'method_setup:ntext',
            'method_read:ntext',
        ],
    ]) ?>

</div>
