<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\components\messageAlerte;
use app\assets\CapteurAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Capteur */

$this->title = "Création d'un Capteur";
$this->params['breadcrumbs'][] = ['label' => 'Capteurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Utilisation des ressources pour les capteur (JS + CSS)
CapteurAsset::register($this);
?>
<div class="capteur-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= tocioRegles::widget(['regle' => "capteurDefinition"])?>

    <?= $this->render('_formCreate', [
        'model' => $model,
    ]) ?>
</div>
