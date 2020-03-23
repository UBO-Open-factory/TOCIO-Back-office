<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Localisationmodule */

$this->title = "Création d'une Localisation de module";
$this->params['breadcrumbs'][] = ['label' => 'Localisationmodules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="localisationmodule-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
