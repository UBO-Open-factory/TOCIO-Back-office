<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Html;


/**
 * Ce widget permet d'afficher les règles de conception ou base de données écrite pour TOCIO.
 * 
 * @author Alexandre PERETJATKO.
 *	@version 19 mars 2020	: APE	- Creation.
 */
class tocioRegles extends Widget {
	public $regle;
	private $message;
	
	public function init(){
		parent::init();
		
		if( $this->regle === null){
			$this->message = "";
		}
		
		
		switch (strtolower($this->regle)) {
			case "capteurdefinition":
				$this->message = "<p>Un <i>Capteur</i> est un élément éléctronique permettant de faire des mesures.</p>
					<p>Il doit être défini avec un <b>nom</b> en toutes lettres et une liste d'ID de <i>Grandeurs</i>.<br/>
						Chaque ID de <i>Grandeur</i> doit être séparée par un point virgule (;)
					</p>";
				break;
			case "moduledefinition":
				$this->message = "<p>Un <i>Module</i> est un ensemble de capteurs.</p>
					</p>";
				break;
			case "grandeurdefinition":
				$this->message = "<p>Une grandeur est composée de :</p>
			    <ul>
			    	<li>un <b>Libelé</b> (la nature de la Grandeur en toutes lettres suivies de l'uinité entre paraenthèse, comme <i>Température (°C)</i>),</li>
			    	<li>un <b>Formattage</b> de la chaine (qui servira à extraire la valeur du capteur de la trame envoyée par le module),</li>
			    	<li>un <b>Type</b> qui sera utilisé pour le type des valeurs (des capteurs) stockée dans la table MySQL.</li>
			    </ul>";
				break;
			case "tablemesuredefinition":
				$this->message = "<p>Une Table de mesure contient un type de données envoyées par un capteur.</p>
					";
				break;
			default:
				$this->message = "<P class='alert alert-danger'>Aucune regles défini pour <b>".$this->regle."</b> dans le fichier ".__FILE__."</P>";
		}
		
	}
	
	public function run(){
		return $this->message;
	}

}
?>