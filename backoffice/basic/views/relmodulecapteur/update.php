<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Relmodulecapteur */

$this->title = 'Mise à jour du Capteur : ' . $model->idModule;
/*
 * Le breadcrumbs n'a pas lieux d'être.
$this->params['breadcrumbs'][] = ['label' => 'Relation module-capteur', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idModule, 'url' => ['view', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur]];
$this->params['breadcrumbs'][] = 'Update';
*/

?>
<div class="relmodulecapteur-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
