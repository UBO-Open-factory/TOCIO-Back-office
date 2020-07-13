<?php
namespace app\components;

use yii\base\Widget;
use app\models\Grandeur;
use onmotion\apexcharts\ApexchartsWidget;
use yii\helpers\Html;


/**
 * Widget pour la page d'accueil du site.
 * Ce widget permet d'afficher les graphiquers sur les tables des mesures.
 * 
 * 	@file GraphiquesWidget.php
 * @author : Alexandre PERETJATKO (APE)
 * @version 13 juil. 2020	: APE	- Création.
 *
 */
class graphiquesWidget extends Widget
{
	public $dataProvider;
	
	
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::init()
	 */
	public function init()	{
		parent::init();
	}
	

	// _____________________________________________________________________________________________
	/**
	 * Génération du code HTML pour l'affichage des graphiques de visualisation des données des 
	 * Grandeurs.
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::run()
	 */
	public function run() {
		
		$l_TAB_Graphique = [];
		
		// ON VA CHERCHER LES NOMS DES TABLES CONTENANT LES MESURES --------------------------------
		foreach( Grandeur::find()->all() as $l_OBJ_Grandeur){
			// SI ON N'A PAS DE MESURES POUR CETTE GRANDEURS, ON NE FAIT RIEN
			// (on ne pvas pas faire un graphique vide...)
			if( $l_OBJ_Grandeur->NbDataTable == 0) continue;
			
			
			// RECUPERATION DES IDENTIFIANTS DES MODULES DANS CETTE TABLE DE MESURES
			$modulesID = (new \yii\db\Query())
							->select(['identifiantModule'])
							->from($l_OBJ_Grandeur->tablename)
							->groupBy(['identifiantModule'])
							->all();
			
							
			// RÉCUPÉRATION DES VALEURS DU MODULE
			$mesures = [];
			foreach( $modulesID as $moduleID){
				$id = $moduleID['identifiantModule'];
				$mesures[$id]	= (new \yii\db\Query())
								->select(['timestamp', 'valeur'])
								->from($l_OBJ_Grandeur->tablename)
								->where(['identifiantModule' => $id])
								->all();
			}
			
			
			// FORMATTAGE DES SERIES DE CETTE TABLE
			/* Elles doivent être de la forme :
			 * [['name' => 'Entity 1',
			 	'data' => [['2018-10-04', 4.66],
			 				['2018-10-05', 5.0],
			 			],
			 ],];
			 */
			$series = [];
			foreach( $mesures as $moduleID => $valeurs){
				$data = [];
				foreach( $valeurs as $valeur){
					$data[] = [$valeur['timestamp'], $valeur['valeur']];
				}
				
				$series[] = ['name' => $moduleID,
							'data'	=> $data];
			}
			
			// ENREGISTREMENT DU GRAPHIQUE
			$l_TAB_Graphique[$l_OBJ_Grandeur->nature] = ApexchartsWidget::widget($this->_formattageGraphique($series));
			
			
		}
		
		// AFFICHAGE DES GRAPHIQUES ----------------------------------------------------------------
		$l_STR_Retour = "";
		foreach( $l_TAB_Graphique as $l_STR_Titre => $l_TAB_Graphique){
			$l_STR_Retour .= Html::tag("div", Html::tag("h2", $l_STR_Titre). $l_TAB_Graphique, ['class' => 'col-lg-12']);
		}
		return $l_STR_Retour;
	}
	

	
	
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Renvoie le paramétrage pour formatter l'affichage des graphiques d'uns serie
	 * 
	 * @param $serie : la série à afficher dans le graphique.
	 */
	private function _formattageGraphique($series){
		return [
				'type' => 'area', // default area
				'height' => '400', // default 350
				'width' => '100%', // default 100%
				'chartOptions' => [
						'chart' => [
								'toolbar' => [
										'show' => true,
										'autoSelected' => 'zoom'
								],
						],
						'xaxis' => [
								'type' => 'datetime',
						],
						
						'dataLabels' => [
								'enabled' => False,
						],
						'stroke' => [
								'curve' => 'smooth',
						],
						'legend' => [
								'verticalAlign' => 'bottom',
								'horizontalAlign' => 'left',
								'style' => ['colors' => ['#FFFFFF', '#E91E63', '#9C27B0']],
						],
						'fill' => [
								'type' => 'gradient',
								'gradient' => [
										'shadeIntensity' =>  1,
										'opacityFrom' =>  0.7,
										'opacityTo' =>  0.5,
										'stops' =>  [0, 90, 100],
								]
						],
				],
				'series' => $series
		];
	}
	

	
}
?>
	