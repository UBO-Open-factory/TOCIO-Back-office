<?php
/**
* @var $this yii\web\View 
*/

$this->title = 'TOCIO : Data';

use app\models\Grandeur;
use app\models\Capteur;
use app\models\Module;
use app\components\messageAlerte;
use app\models\TmLuminositlux;
use app\models\TmTemperaturec;

$grandeurs 	= Grandeur::findBySql("SELECT * FROM grandeur")->all();
$capteurs 	= Capteur::findBySql("SELECT * FROM capteur")->all();
$modules	= Module::findBySql("SELECT * FROM module")->all();
$l_INT_LocalisationModule	= Module::find()->indexBy('id')->count();
$l_INT_MesuresLuminosite	= TmLuminositlux::find()->indexBy('id')->count();
$l_INT_MesuresTemperaturC	= TmTemperaturec::find()->indexBy('id')->count();
$l_INT_NombreTableMesure 	= Grandeur::find()->where(['like', 'tablename' , 'tm_'])->count();

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
        <h1>TOCIO : Data</h1>
        <p class="lead">Ce site web permet de configurer les capteurs et modules pour qu'ils puissent stocker des données dans la base.</p>
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
			                <a class="card-link" href="/module/index">Voir les Modules &raquo;</a>
						</div>
					</div>
	            </div>
	            <div class="col-lg-3">
	            	<div class="card text-white bg-secondary " >
		            	<div class="card-header">Nombre de capteur</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= count($capteurs);?></h1>
			                <a class="card-link" href="/capteur/index">Voir les Capteurs &raquo;</a>
						</div>
					</div>
	            </div>
	            <div class="col-lg-3">
					<div class="card text-white bg-secondary " ">
		            	<div class="card-header">Nombre de grandeurs</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= count($grandeurs);?></h1>
			                <a class="card-link" href="/grandeur/index">Voir les grandeurs &raquo;</a>
						</div>
					</div>
				</div>
	            <div class="col-lg-3">
	            	<div class="card text-white bg-secondary " >
	            	<div class="card-header">Nombre de table de mesure</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?php echo $l_INT_NombreTableMesure; ?></h1>
			                <a class="card-link" href="/grandeur/index">Voir les table de mesures &raquo;</a>
						</div>
					</div>
	            </div>
	            <div class="col-lg-3">
	            	<div class="card text-white bg-secondary " >
	            	<div class="card-header">Nombre de mesures dans la table luminosité</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?php echo $l_INT_MesuresLuminosite; ?></h1>
						</div>
					</div>
	            </div>
	            <div class="col-lg-3">
	            	<div class="card text-white bg-secondary " >
	            	<div class="card-header">Nombre de mesures dans la table température °C</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?php echo $l_INT_MesuresTemperaturC; ?></h1>
						</div>
					</div>
	            </div>
	        </div>
        </div>
    </div>
</div>
<?php /*@todo  Ajouter un lien sur l'API */
echo messageAlerte::widget(['type' => "todo", "message" => "Ajouter un lien sur l'API "]); ?>