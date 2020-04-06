<?php

use yii\helpers\Html;
use app\components\messageAlerte;

/* @var $this yii\web\View */
/* @var $model app\models\Relmodulecapteur */

$this->title = "Ajout d'un capteur dans le module";
$this->params['breadcrumbs'][] = ['label' => 'Relmodulecapteurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relmodulecapteur-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
