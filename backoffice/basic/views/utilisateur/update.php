<?php

use yii\helpers\Html;
use app\components\messageAlerte;

/* @var $this yii\web\View */
/* @var $model app\models\Utilisateurs */

$this->title = "Mise à jour de l'Utilisateur: " . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Utilisateurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="utilisateurs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php /*@todo  Si le mot de passe est inchangé, il ne faut pas le sauver.*/
echo messageAlerte::widget(['type' => "todo", "message" => "Si le mot de passe est inchangé, il ne faut pas le sauver."]); ?>