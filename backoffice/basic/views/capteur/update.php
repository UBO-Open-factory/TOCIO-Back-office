<?php

use yii\helpers\Html;
use app\components\messageAlerte;
use app\assets\CapteurAsset;


/* @var $this yii\web\View */
/* @var $model app\models\Capteur */

$this->title = 'Mise à jour du Capteur: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Capteurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

// Utilisation des ressources pour les capteurs (JS + CSS)
CapteurAsset::register($this);
?>
<div class="capteur-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
