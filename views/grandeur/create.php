<?php
/**
 * 	@file create.php
 * @todo : Faire une auto-completion sur le libellé de la grandeur
 * @todo : Transformer le séparateur en virgule 
 */

use yii\helpers\Html;
use app\components\messageAlerte;
use app\components\tocioRegles;

/* @var $this yii\web\View */
/* @var $model app\models\Grandeur */

$this->title = "Création d'une Grandeur";
$this->params['breadcrumbs'][] = ['label' => 'Grandeurs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grandeur-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle' => 'grandeurDefinition']); ?>
    <?php echo messageAlerte::widget(['type' => "todo", "message" => "Transformer le séparateur en virgule."]); ?>
    <?php echo messageAlerte::widget(['type' => "todo", "message" => "Faire une auto-completion sur le libellé de la grandeur."]);?>
    <?php echo messageAlerte::widget(['type' => "todo", "message" => "Afficher un message si la Grandeur sasie existe ET qu'elle a un type différent."]);?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
