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
			case "tociodefinition" :
				$this->message = "Le projet TOCIO permet de déployer un ensemble de Modules comportant des Capteurs afin de faire des remontées de Mesures de Grandeurs.<br/>
						Ce Back Office (BO) permet d'administrer cette flotte de Modules et fourni une API vers la base de stockage des données.";
				break;
			case "capteurdefinition":
				$this->message = "<p>Un <i>Capteur</i> est un élément éléctronique permettant de faire des <i>Mesures</i>.</p>
					<p>Il doit être défini avec un <b>nom</b> en toutes lettres et une <b>liste</b> de <i>Grandeurs</i>.<br/>
						Une <i>Grandeur</i> est la caractéristique d'une Mesure.<br/>
						La liste doit contenir au moins un élément.
					</p>";
				break;
			case "encodageformatdefinition":
			$this->message = "Le formattage des valeurs mesurées est fait selon la règle <b>chiffre_avant_la_virgule,chiffre_apres_la_virgule</b>.</br> Par exemple pour une température de 12.5°C encodée selon le schéma -3.2, il faut écrire +01250, pour -1.02°C il faut écrire -00102";
				break;
			case "moduledefinition":
				$this->message = "Un <i>Module</i> est un ensemble de capteurs.";
				break;
			case "utilisateursgroupe":
				$this->message = "<p>un <i>Utilisateur</i> est une personne pouvant accéder au BackOffice de TOCIO (ce site).<br/>
					Chaque <i>Utilisateur</i> doit être rattaché à un <i>Groupe</i> afin de lui définir des droits d'accés.</p>
					<p>Le <i>Groupe</i> <b>Utilisateur</b> à simplement des droits en lecture sur le contenu du site.<br/>
					Le <i>Groupe</i> <b>Administrateur</b> à tout les droits sur l'ensemble du contenu du site. Il peut créer, modifier et supprimer. Ne donnez pas ce droit à n'importe qui...</p>
				";
				break;
			case "localisationmodule":
				$this->message = "<p>La <i>Localisation</i> d'un <i>Module</i> permet de définir un emplacement géolocalisé d'un <i>Module</i>.</p>
					Une <i>Localisation</i> ne peut pas être supprimée si un <i>Module</i> l'utilise.";
				break;
			case "grandeurdefinition":
				$this->message = "<p>Une grandeur est composée de :</p>
			    <ul>
			    	<li>un <b>Libelé</b> (la nature de la Grandeur en toutes lettres suivies de l'unité entre parenthèses, comme <i>Température (°C)</i>),</li>
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