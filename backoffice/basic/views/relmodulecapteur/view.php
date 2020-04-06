<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Relmodulecapteur */

$this->title = $model->idModule;
$this->params['breadcrumbs'][] = ['label' => 'Relmodulecapteurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="relmodulecapteur-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur], [
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
            'idModule',
            'idCapteur',
            'nomcapteur:ntext',
            'x',
            'y',
            'z',
        ],
    ]) ?>

</div>
