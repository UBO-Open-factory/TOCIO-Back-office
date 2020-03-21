<?php

/* @var $this yii\web\View */

$this->title = 'TOCIO : Data';

use app\models\Grandeur;
use app\models\Capteur;
use app\models\Module;

$grandeurs 	= Grandeur::findBySql("SELECT * FROM grandeur")->all();
$capteurs 	= Capteur::findBySql("SELECT * FROM capteur")->all();
$modules	= Module::findBySql("SELECT * FROM module")->all();
$localisationModule	= Module::findBySql("SELECT * FROM localisationmodule")->all();
$l_INT_Tables = Yii::$app->db->createCommand('SELECT COUNT(id) FROM tablemesure')->queryScalar();
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
			['Localisation de module',	<?= count($localisationModule);?>],
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
        	<div class="col-xs-4">
        		<h1 class="text-center">Répartition des données de paramétrage</h1>
        		<p class="text-center">(Grandeurs, Capteurs, Modules & localisation de modules)</p>
        		<div id="donutchart" style="width: 100%; height: 400px;"></div>
        	</div>
            <div class="col-lg-3">
            	<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
	            	<div class="card-header">Nombre de modules</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= count($modules);?></h1>
			                <a class="card-link" href="/module/index">Voir les Modules &raquo;</a>
						</div>
					</div>
				</div>
            </div>
            <div class="col-lg-3">
				<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
	            	<div class="card-header">Nombre de grandeurs</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= count($grandeurs);?></h1>
			                <a class="card-link" href="/grandeur/index">Voir les grandeurs &raquo;</a>
						</div>
					</div>
				</div>
			</div>
            <div class="col-lg-3">
            	<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
	            	<div class="card-header">Nombre de capteur</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= count($capteurs);?></h1>
			                <a class="card-link" href="/capteur/index">Voir les Capteurs &raquo;</a>
						</div>
					</div>
				</div>
            </div>
            <div class="col-lg-3">
            	<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
	            	<div class="card-header">Nombre de table de mesure</div>
						<div class="card-body">
							<h1 class="card-text text-center"><?= $l_INT_Tables ;?></h1>
			                <a class="card-link" href="/tablemesure/index">Voir les tables &raquo;</a>
						</div>
					</div>
				</div>
            </div>
            <div class="col-lg-3">
            	<div class="card text-white bg-info mb-3" style="max-width: 20rem;">
	            	<div class="card-header">Nombre de mesures</div>
						<div class="card-body">
							<p>A faire</p>
						</div>
					</div>
				</div>
                
            </div>
        </div>


    

    </div>
</div>
