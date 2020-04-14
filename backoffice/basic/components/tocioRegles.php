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
				$this->message = "<p>Un <i>Capteur</i> est un élément éléctronique permettant de faire des <i>Mesures</i>.</p>
					<p>Il doit être défini avec un <b>nom</b> en toutes lettres et une <b>liste</b> de <i>Grandeurs</i>.
						Une <i>Grandeur</i> est la caractéristique d'une Mesure.
					</p>";
				break;
			case "encodageformatdefinition":
			$this->message = "Le formattage des valeurs mesurées est fait selon la règle <b>chiffre_avant_la_virgule,chiffre_apres_la_virgule</b>.</br> Par exemple pour une température de 12.5°C encodée selon le schéma -3.2, il faut écrire +01250, pour -1.02°C il faut écrire -00102";
				break;
			case "moduledefinition":
				$this->message = "<p>Un <i>Module</i> est un ensemble de capteurs.</p>";
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