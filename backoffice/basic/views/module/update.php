<?php

use yii\helpers\Html;
use app\components\messageAlerte;

/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = 'Mise à jour du Module: ' . $model->identifiantReseau;
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->identifiantReseau, 'url' => ['view', 'id' => $model->identifiantReseau]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="module-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php /*@todo  Pouvoir ajouter une localisation à partir de cette page*/
echo messageAlerte::widget(['type' => "todo", "message" => "Pouvoir ajouter une localisation à partir de cette page"]); ?>