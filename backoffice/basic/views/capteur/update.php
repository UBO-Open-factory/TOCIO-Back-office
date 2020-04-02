<?php

use yii\helpers\Html;
use app\components\messageAlerte;

/* @var $this yii\web\View */
/* @var $model app\models\Capteur */

$this->title = 'Update Capteur: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Capteurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="capteur-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php /*@todo  Attention, pour  l'instant la grandeur n'est pas enregistrée !*/
echo messageAlerte::widget(['type' => "todo", "message" => "Attention, pour  l'instant la grandeur n'est pas enregistrée !"]); ?>