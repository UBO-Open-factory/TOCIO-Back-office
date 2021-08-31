<?php
use yii\helpers\Html;
use app\components\tocioRegles;
use app\components\messageAlerte;
use yii\helpers\Url;


$this->title = 'Grandeurs';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($tablename) ?></h1>

Code pour l'intégration de la table de mesure : <?php echo $tablename?> ( <?php echo $title;?> ) dans Grafana.
<div class="code">
<pre>
<?php echo $code;?>
</pre>
</div>