<?php
namespace app\components;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\VarDumper;
use app\models\RelCapteurgrandeur;
use app\models\RelPositionCapteur;
use app\models\Position;

class modulesWidget extends Widget
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
	 * Génération du code HTML pour l'affichage des modules.
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::run()
	 */
	public function run() {
		// Le bouton pour plier/déplier les boites.
		$l_STR_BtnPliage = Html::tag("span","", ['class'	=> "triangle pull-right glyphicon glyphicon-triangle-bottom"]);
		$l_STR_BtnDelete = Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
		
		
		$models = array_values($this->dataProvider->getModels());
		$modules = [];
		

		// PARCOURS DE CHACUN DES MODELS
		
		foreach ($models as $index => $l_OBJ_Module) {
			// FORMATAGE DE LA TRAME (PAYLOAD" ATTENDU POUR CE MODULE ------------------------------
			$formatTrame = [];
			
			// L'identification du capteur
			// @see https://www.yiiframework.com/doc/guide/2.0/fr/helper-html
			$formatTrame[] = Html::tag("button ",$l_OBJ_Module->identifiantReseau,["type" => "button", "class" => "btn btn-primary disabled"]);
			
			// Le séparateur
			$formatTrame[] = Html::tag("button", "#",[	"type" => "button", 
														"class" => "btn btn-primary disabled",
														"data-toggle"	=> "tooltip" ,
														"data-placement" => "bottom",
														"title" => "",
														"data-original-title"	=> "Popover Title",
														]);

			
			
			
			// BOUTONS D'ÉDITION DU MODULE ---------------------------------------------------------
			$l_TAB_BtnEditionModule 	= [];
			$l_TAB_BtnEditionModule[]	= $this->_btnEdition("module/update", "glyphicon glyphicon-pencil", $l_OBJ_Module->identifiantReseau);
			$l_TAB_BtnEditionModule[]	= $this->_btnEdition("module/delete", "glyphicon glyphicon-trash", $l_OBJ_Module->identifiantReseau);
			$l_TAB_BtnEditionModule[]	= $this->_getModuleDeleteButton([	'action' => "/module/delete",'id' => $l_OBJ_Module->identifiantReseau]);
			
		
			
			// RECUPERATION DES CAPTEURS RATTACHÉS À CE MODULE -------------------------------------
			$capteurs = [];
			$capteursNames = [];
			foreach( $l_OBJ_Module->idCapteurs as $l_OBJ_Capteur){
				// Boutons d'édition du capteur custom
				$l_TAB_BtnCustomCapteur	= [];

				
				// La customisation de ce capteur pour ce module
				$l_STR_Position				= "";
				$l_STR_CustomCapteurName 	="";
				foreach( $l_OBJ_Capteur->relModulecapteurs as $l_OBJ_ModuleCapteur){
					// On trouve le Capteur de notre module dans la table des relation module/capteurs					
					if( $l_OBJ_ModuleCapteur->idModule == $l_OBJ_Module->identifiantReseau) {
						$l_STR_Position 				= $l_OBJ_ModuleCapteur['x']. "," .$l_OBJ_ModuleCapteur['y']. "," .$l_OBJ_ModuleCapteur['z'];
						$l_STR_CustomCapteurName		= $l_OBJ_ModuleCapteur['nomcapteur'];
						
						// Bouton d'édition du capteur
						$l_TAB_BtnCustomCapteur[]	= $this->_btnEditionCustomCapteur("relmodulecapteur/update", "glyphicon glyphicon-pencil", $l_OBJ_ModuleCapteur['idModule'], $l_OBJ_ModuleCapteur['idCapteur']);
						$l_TAB_BtnCustomCapteur[]	= Html::a($l_STR_BtnDelete,
															["relmodulecapteur/delete", "idModule" => $l_OBJ_ModuleCapteur['idModule'], "idCapteur" => $l_OBJ_ModuleCapteur['idCapteur']],
															['data-pjax' => "0",
																	"aria-label" => "Supprimer",
																	"title" => "Supprimer",
																	"data-confirm" => "Êtes-vous sûr de vouloir détacher ce Capteur de ce Module ?",
																	"data-method"=>"post"]);
					}
				}
				$l_STR_Position	= $this->_legende($l_STR_Position, "Coordonnées");

				
				// Le nom officiel du capteur
				$l_STR_NomCapteur	= $l_OBJ_Capteur->nom;
				$capteursNames[]	= $l_OBJ_Capteur->nom;
				
				
				// Contenu de la boite du capteur sur 2 colonnes
				$contents = []; 					
				$contents[] = "<div class='row'>";
				$contents[] = "<div class='col-md-3'>";
				$contents[] = Html::tag("h4", $l_STR_NomCapteur,["class" => "card-title"]);
				$contents[] = "</div>";
				$contents[] = "<div class='col-md-2'>";
				$contents[] = $l_STR_Position;
				$contents[] = "</div>";
				$contents[] = "<div class='col-md-7'>";
				$contents[] = "		<div class='row'>";

				
				// Recuperation de chacune des grandeurs rattachées à ce capteur
				foreach( RelCapteurgrandeur::find()->where(["idCapteur" => $l_OBJ_Capteur->id])->all() as $l_OBJ_Grandeurs){
					// Formattage des libellés de la grandeur
					$format = $l_OBJ_Grandeurs->idGrandeurs['formatCapteur'];
					$l_STR_Nature		= $this->_toolTip($l_OBJ_Grandeurs->idGrandeurs['nature'], "Nature de la mesure");
					$l_STR_Format		= $this->_toolTip($format, "Format d'encodage de la ".$l_OBJ_Grandeurs->idGrandeurs['nature'].".\nExemple : ".$this->_exempleFormatGrandeur($format));
					$l_STR_GrandeurID	= $l_OBJ_Grandeurs->idGrandeurs['id'];

					
					// Boutons d'édition du format
					$l_TAB_BtnFormat	= [];
					$l_TAB_BtnFormat[]	= $this->_btnEdition("grandeur/update", "glyphicon glyphicon-pencil", $l_STR_GrandeurID);
					$l_TAB_BtnFormat[]	= $this->_btnEdition("grandeur/delete", "glyphicon glyphicon-trash", $l_STR_GrandeurID);
					
					
					
					// Ajout du format dans la trame
					$formatTrame[] = Html::tag("button ",$l_STR_Format,["type" => "button", "class" => "btn btn-primary disabled"]);

					
					// Formattage de l'affichage de la grandeur
					$contents[] = Html::tag("div",
											$l_STR_Nature,
											["class" => "col-md-8"]);
					$contents[] = Html::tag("div",
											$l_STR_Format,
											["class" => "col-md-2"]);
					$contents[] = Html::tag("div",
							implode(" ", $l_TAB_BtnFormat),
											["class" => "col-md-2"]);
				}
				$contents[] = "		</div>";
				$contents[] = "</div>";
				$contents[] = "</div>";
				
				
				
				// Boite autour du capteur
				$capteurs[] = $this->_cardBox([	"header" 	=> $l_STR_CustomCapteurName. " ".implode(" ", $l_TAB_BtnCustomCapteur)." ".$l_STR_BtnPliage,
												"content"	=> implode("", $contents),
												"class"		=> "border-info mb-3 px-0 Capteur",
												"style" 	=> null,
										]);
			}

			
			// CONSTRUCTION DU CONTENU DU MODULE ---------------------------------------------------
			// formattage des libellés
			$l_STR_localisationModule	= $this->_toolTip($l_OBJ_Module->localisationModule->description, "Localisation du module");
			$l_STR_Nom 					= $this->_toolTip($l_OBJ_Module->nom, "Nom du module");
			$l_STR_IdentifiantReseau 	= $this->_toolTip($l_OBJ_Module->identifiantReseau, "Identifiant réseau du module");
			$l_STR_Description			= $this->_toolTip($l_OBJ_Module->description, "Description du module");

			
			// Bouton d'ajout d'un capteur
			$l_STR_Icon		= Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]);
			$l_STR_Temp 	= Html::button($l_STR_Icon. " Ajouter un capteur", ["class" => "btn btn-info pull-right"]);
			$l_STR_BtnAjoutCapteur = Html::a($l_STR_Temp, ['relmodulecapteur/create', 'idModule' => $l_OBJ_Module['identifiantReseau']], ['class' => 'profile-link']);
			
			
			// Construction du contenu de la boite sur 3 colonnes.
			$contents = [];
			$contents[] = "<div class='row'>";
			$contents[] = "<div class='col-md-3'>";
			$contents[] = Html::tag("h4", $l_STR_Nom." ". implode(" ", $l_TAB_BtnEditionModule),["class" => "card-title"]);
			$contents[] = Html::tag("p", $this->_legende($l_STR_Description, "Description"));
			$contents[] = Html::tag("p", $this->_legende($l_STR_localisationModule, "Localisation"));
			$contents[] = Html::tag("p", $this->_legende($l_STR_IdentifiantReseau, "Identifiant réseau"));
			$contents[] = "</div>";
			$contents[] = "<div class='col-md-9'>";
			$contents[] = implode("", $capteurs);
			$contents[] = $l_STR_BtnAjoutCapteur;
			$contents[] = "</div>";
			$contents[] = "<div class='col-md-12'>";
			$contents[] = Html::tag("p", $this->_legende(implode("", $formatTrame), "Format de la trame"));
			$contents[] = "</div>";
			$contents[] = "</div>";
			
			
			
			
			// CONSTRUCTION DE LA BOITE DU MODULE --------------------------------------------------
			$modules[] = $this->_cardBox(["header" 	=> $l_STR_Nom." ".implode(" | ", $capteursNames) .$l_STR_BtnPliage,
											"titre" 	=> $l_STR_Description,
											"content"	=> implode("", $contents),
											"class"		=> "card border-success  mb-3 px-0 Module",
											"style" 	=> "max-width: 90rem",
										]);
		}
		
		
		return Html::tag("div", implode("", $modules), array("class" => "Modules"));
	}
	

	
	
	// _____________________________________________________________________________________________
	/**
	 * Construction d'un lien de suppression d'un module
	 * 
	 * @param array $params : 	['action'] 	= l'action à appeler lors de la validation du formulaire.
	 *	 						['id'] 		= l'ID du module à supprimer
	 * @return string
	 */
	private function _getModuleDeleteButton($params){
		// Le picto
		$l_STR_SubmitButton = Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);

		// Le lien
		return Html::a($l_STR_SubmitButton, 
						[$params['action'], "id" => $params['id']], 
						['data-pjax' => "0", 
						"aria-label" => "Supprimer", 
						"title" => "Supprimer", 
						"data-confirm" => "Êtes-vous sûr de vouloir supprimer cet élément ?",  
						"data-method"=>"post"]);
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Permet d'afficher une boite (Cards) au format bootstrap, comme ci-dessous :
	  	<div class="card border-secondary mb-3" style="max-width: 20rem;">
		  <div class="card-header">Header</div>
		  <div class="card-body">
		    <h4 class="card-title">Secondary card title</h4>
		    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
		  </div>
		</div>
	 * 
	 * $params, doit contenir :
	 * 		header : Le titre de la boite (dans le header)
	 * 		class : Largeur bootstrap (en colonne). par exemple (col-md-3)
	 * 		style : style à appliquer à la boite.
	 * 		titre : le titre du contenu (lapartit "title")
	 * 		text : Le contenu de la boite ( dans la partie "text")
	 * 		content : si présent, écrase titre et text. 
	 * 
	 * 
	 * 
	 * @see https://bootswatch.com/slate/
	 * @param array $params tableaui de la forme cle, valeur.
	 * @return string HTML.
	 */
	private function _cardBox($params) {
		// ENTETE		
		$header 	= ($params['header'] !== null) ? Html::tag("div", $params['header'], array("class" => "card-header toggleAffichage")):"";
		
		// CONTENU
		if( !isset($params['content']) ){
			$titre 	= ($params['titre'] !== null) ? Html::tag("h4",$params['titre'], array("class" => "card-title") ):"";
			$text	= ($params['text'] !== null) ? Html::tag('p', $params['text'], array("class"=>"card-text")):"";
		} else {
			$titre = "";
			$text = $params['content'];
		}
		$body	= Html::tag("div", $titre . $text, array("class" => "card-body"));
		
		
		// BOITE
		$class 	= ($params['class'] !== null ) ? $params['class'] : "";
		$style 	= ($params['style'] !== null ) ? $params['style'] : "";
		$cardBox = Html::tag("div",$header . $body , array("class" => "card  ".$class,
															"style" => $style
															));
		// AFFICHAGE DE LA BOITE
		return $cardBox;
	}
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Retourne un exemeple de formattage de grandeur en fonctoin du format fourni en paramètre.
	 * @param string $format le format tel que défini dans le BDD pour la grandeur.
	 * @return string
	 */
	private function _exempleFormatGrandeur($format){
		// Extraction de la partie avant et apres la virgule
		list($l_STR_Avant, $l_INT_Apres) = explode(",", $format);
		$l_INT_Avant = abs($l_STR_Avant);	// On prend la valeur absolue de ce qui est avant la virgule.

		
		// Si ce qui est avant la virgule doit contenir un signe
		$l_STR_Signe = "";
		if( strpos($l_STR_Avant, "-") !== false ){
			$l_STR_Signe = "-";
		}
		
		$l_TAB_Avant = [];
		for( $i=0; $i < $l_INT_Avant; $i++){
			$l_TAB_Avant[] = $i;
		}
		$l_TAB_Apres= [];
		
		for( $i=0; $i < $l_INT_Apres; $i++){
			$l_TAB_Apres[] = $i;
		}
		
		return $l_STR_Signe.implode("", $l_TAB_Avant).implode("", $l_TAB_Apres);
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Permet de générer le code HTML pour l'affichage d'un tool tip.
	 * 
	 * @param string $mot : le mot sur lequel va être mis le tool tip
	 * @param string $legend : la légende du mot.
	 * @return string
	 */
	private function _toolTip($mot, $legend){
		return '<a href="#" data-toggle="tooltip" data-placement="bottom" title="'.$legend.'">'.$mot.'</a>';
		
	}
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Permet d'afficher un filedset autour d'un mot avec une légende.
	 * @param string $mot : le mot sur lequel va être mis le tool tip
	 * @param string $legend : la légende du mot.
	 * @return string
	 */
	private function _legende($mot, $legend){
		$l_STR_Chaine	= "";
		$l_STR_Chaine	.= Html::tag("div", $legend, ['class' => "form-text text-muted"]);
		$l_STR_Chaine	.= Html::tag("div", $mot);
		
		return $l_STR_Chaine;
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Génère le code HTML pour un bouton d'édition.
	 * @param string $link 	à déclancher lorsque l'on clique sur le bouton
	 * @param string $icon	du bouton.
	 * @param string $id	de l'objhet à passer en paramètre.
	 * @return string
	 */
	private function _btnEdition($link,$icon, $id){
		$btn = Html::tag("span ", "",["class" => $icon]);
		return Html::a($btn, [$link, 'id' => $id]);
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Génère le code HTML pour un bouton d'édition.
	 * @param string $link 	à déclancher lorsque l'on clique sur le bouton
	 * @param string $icon	du bouton.
	 * @param string $id	de l'objhet à passer en paramètre.
	 * @return string
	 */
	private function _btnEditionCustomCapteur($link,$icon, $idModule, $idCapteur){
		$btn = Html::tag("span ", "",["class" => $icon]);
		return Html::a($btn, [$link, 'idModule' => $idModule, 'idCapteur' => $idCapteur]);
	}
}
?>
