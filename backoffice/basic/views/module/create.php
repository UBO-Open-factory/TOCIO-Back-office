<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = "Création d'un Module";
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Enregistrement de L'URl pour le retour avec Url::previous()
Url::remember();
?>
<div class="module-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle'=>'moduleDefinition']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
