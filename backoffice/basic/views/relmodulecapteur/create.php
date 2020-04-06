<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Relmodulecapteur */

$this->title = 'Create Relmodulecapteur';
$this->params['breadcrumbs'][] = ['label' => 'Relmodulecapteurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="relmodulecapteur-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
