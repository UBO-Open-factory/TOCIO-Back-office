<?php

use yii\helpers\Html;
use app\components\messageAlerte;
use app\components\tocioRegles;

/* @var $this yii\web\View */
/* @var $model app\models\Utilisateurs */

$this->title = "Création d'un Utilisateur";
$this->params['breadcrumbs'][] = ['label' => 'Utilisateurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="utilisateurs-create">
	<?php echo tocioRegles::widget(['regle' => 'utilisateursgroupe']); ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>