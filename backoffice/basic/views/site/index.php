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
use onmotion\apexcharts\ApexchartsWidget;



$grandeurs 	= Grandeur::findBySql("SELECT * FROM grandeur")->count();
$capteurs 	= Capteur::findBySql("SELECT * FROM capteur")->count();
$modules	= Module::findBySql("SELECT * FROM module")->count();
$l_INT_LocalisationModule	= Module::find()->indexBy('id')->count();
$l_INT_NombreTableMesure 	= Grandeur::find()->where(['like', 'tablename' , 'tm_'])->count();

$identity = Yii::$app->user->identity;	// null si non authentifié
$series = [$grandeurs +0, $capteurs +0, $modules +0, $l_INT_LocalisationModule +0, $l_INT_NombreTableMesure +0];
?>

    
    
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
	        		<?php echo ApexchartsWidget::widget([
	        						'type' => 'donut', // default area
	        						'height' => '400', // default 350
	        						'width' => '90%',
	        						'chartOptions' => [
	        								'chart' => [
	        										'toolbar' => [
	        												'show' => true,
	        												'autoSelected' => 'zoom'
	        										],
	        								],
	        								'fill' => 		[ 'type' => 'gradient'],
	        								'labels' =>		['Grandeurs', 'Capteurs', 'Modules', 'Localisation', 'Tables de mesure'],
	        								'dataLabels' => [ 'enabled' => true,
	        													'style' => ['colors' => ['#FFFFFF']]],
	        								'legend' =>		['onItemHover' => ['highlightDataSeries' => true],
	        													'style' => ['colors' => ['#FFFFFF']]],
	        								'stroke' => [
	        										'show' => true,
	        										'colors' => ['transparent']
	        								],
	        						],
	        				'series' => $series
	        				]
	        				);?>
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
							<h1 class="card-text text-center"><?= $capteurs?></h1>
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
							<h1 class="card-text text-center"><?= $grandeurs?></h1>
							<?php if( $identity != null){?>
			                <a class="card-link" href=<?= Url::toRoute('/grandeur/index')?>>Voir les grandeurs &raquo;</a>
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
			                <a class="card-link" href=<?= Url::toRoute('/grandeur/index')?>>Voir les tables de mesures &raquo;</a>
			                <?php }?>
						</div>
					</div>
	            </div>
	        </div>
        </div>
    </div>
</div>