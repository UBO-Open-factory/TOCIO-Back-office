<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\components\messageAlerte;

/* @var $this yii\web\View */
/* @var $model app\models\Capteur */

$this->title = "Création d'un Capteur";
$this->params['breadcrumbs'][] = ['label' => 'Capteurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capteur-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= tocioRegles::widget(['regle' => "capteurDefinition"])?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
<?= messageAlerte::widget(['type' => "Warning", 'message' => "Attention Pour l'instant les ID des grandeurs sont à saisir à la main"])?>
</div>
