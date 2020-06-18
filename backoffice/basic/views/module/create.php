<?php

use yii\helpers\Html;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\Url;
use app\assets\ModuleAsset;
use yii2mod\alert\Alert;


/* @var $this yii\web\View */
/* @var $model app\models\Module */

$this->title = "Création d'un Module";
$this->params['breadcrumbs'][] = ['label' => 'Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Utilisation des ressources pour les modules (JS)
ModuleAsset::register($this);

// Enregistrement de L'URl pour le retour avec Url::previous()
Url::remember();

// Affichage des messages d'erreur éventuels
echo Alert::widget();
?>
<div class="module-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo tocioRegles::widget(['regle'=>'moduleDefinition']); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php /*@todo  La validation du formulaire ne mache pas car elle fait appel à la fonction ajax qui n'affiche pas les message d'erreur*/
echo messageAlerte::widget(['type' => "todo", "message" => "Le formulaire n'affiche pas les message d'erreur lorsqu'on le valide et qu'un champ n'est pas saisie. Cela vient du fait qu'il passe par la requète ajax pour la validaiton et que c'e n'est pas traiter -> à faire."]); ?>