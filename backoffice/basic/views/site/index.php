<?php
/**
* @var $this yii\web\View 
*/

$this->title = Yii::$app->name;

use app\models\Grandeur;
use app\models\Capteur;
use app\models\Module;
use app\components\messageAlerte;
use app\models\TmLuminositlux;
use app\models\TmTemperaturec;
use yii\helpers\Url;
use Codeception\Lib\Connector\Yii2;
use yii\filters\HostControl;

$grandeurs 	= Grandeur::findBySql("SELECT * FROM grandeur")->all();
$capteurs 	= Capteur::findBySql("SELECT * FROM capteur")->all();
$modules	= Module::findBySql("SELECT * FROM module")->all();
$l_INT_LocalisationModule	= Module::find()->indexBy('id')->count();
$l_INT_NombreTableMesure 	= Grandeur::find()->where(['like', 'tablename' , 'tm_'])->count();

$identity = Yii::$app->user->identity;	// null si non authentifié
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load("current", {packages:["corechart"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['Task', 'Nombre'],
			['Capteurs',	<?= count($capteurs);?>],
			['Grandeurs',	<?= count($grandeurs);?>],
			['Localisation de module',	<?= $l_INT_LocalisationModule;?>],
			['Modules',		<?= count($modules);?>]
        ]);

        var options = {
          title: '',
          backgroundColor: 'transparent',
          pieHole: 0.4,
          legend: 'none',
          pieSliceText: 'label',
          textStyle:{color: '#FFFFFF'}
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
</script>
    
    
<div class="site-index">
    <div class="jumbotron">
        <h1><?php echo Yii::$app->name;?></h1>
        <p class="lead"><?= Yii::$app->version?><br/></p>
    </div>

    <div class="body-content">
    	<div class="row">
	        <div class="col-lg-12">
	        	<div class="col-sm-5">
	        		<h1 class="text-center">Répartition des données de paramétrage</h1>
	        		<p class="text-center">(Grandeurs, Capteurs, Modules & localisation de modules)</p>
	        		<div id="donutchart" style="width: 100%; height: 400px;"></div>
	        	</div>
	        	
	        	
	            <div class="col-lg-3">
	            	<div class="card text-white bg-secondary " >
		            	<div class="card-header">Nombre de modules</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= $l_INT_LocalisationModule;?></h1>
							<?php if( $identity != null){?>
			                <a class="card-link" href="<?= Url::toRoute('/module/index')?>">Voir les Modules &raquo;</a>
			                <?php }?>
						</div>
					</div>
	            </div>
	            <div class="col-lg-3">
	            	<div class="card text-white bg-secondary " >
		            	<div class="card-header">Nombre de capteur</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= count($capteurs);?></h1>
							<?php if( $identity != null){?>
			                <a class="card-link" href="<?= Url::toRoute('/capteur/index')?>">Voir les Capteurs &raquo;</a>
			                <?php }?>
						</div>
					</div>
	            </div>
	            <div class="col-lg-3">
					<div class="card text-white bg-secondary " ">
		            	<div class="card-header">Nombre de grandeurs</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= count($grandeurs);?></h1>
							<?php if( $identity != null){?>
			                <a class="card-link" href=<?= Url::toRoute('/grandeur/index')?>">Voir les grandeurs &raquo;</a>
			                <?php }?>
						</div>
					</div>
				</div>
	            <div class="col-lg-3">
	            	<div class="card text-white bg-secondary " >
	            	<div class="card-header">Nombre de table de mesure</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?php echo $l_INT_NombreTableMesure; ?></h1>
							<?php if( $identity != null){?>
			                <a class="card-link" href=<?= Url::toRoute('/grandeur/index')?>">Voir les table de mesures &raquo;</a>
			                <?php }?>
						</div>
					</div>
	            </div>
	        </div>
        </div>
    </div>
</div>