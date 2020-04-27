<?php

use yii\helpers\Html;
use app\components\messageAlerte;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = 'Mise à jour du Module: ' . $model->identifiantReseau;
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identifiantReseau, 'url' => ['view', 'id' => $model->identifiantReseau]];
$this->params['breadcrumbs'][] = 'Update';

// Enregistrement de L'URl pour le retour avec Url::previous()
Url::remember();
?>
<div class="module-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
