<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Relcapteurgrandeur */

$this->title = $model->idCapteur;
$this->params['breadcrumbs'][] = ['label' => 'Relcapteurgrandeurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="relcapteurgrandeur-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idCapteur' => $model->idCapteur, 'idGrandeur' => $model->idGrandeur], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idCapteur' => $model->idCapteur, 'idGrandeur' => $model->idGrandeur], [
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
            'idCapteur',
            'idGrandeur',
        ],
    ]) ?>

</div>
