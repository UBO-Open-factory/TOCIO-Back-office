<?php
/**
 * Widget pour l'affichage de la page des Modules.
 * 
 * 	@file modulesWidget.php
 * @author : Alexandre PERETJATKO (APE)
 * @version 09 avr. 2020	: APE	- Création
 */
namespace app\components;

use yii\base\Widget;
use yii\bootstrap\Html;
use app\models\Relcapteurgrandeur;
use app\models\relmodulecapteur;
use app\models\Capteur;
use app\models\Grandeur;
use app\models\Cartes;
use app\models\Method;
use function Opis\Closure\serialize;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class modulesWidget extends Widget
{
	public $dataProvider;
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::init()
	 * 	@version 1 mai 2020	: APE	- Ajout de l'écriture des variables de chemins pour le Javascript.
	 */
	public function init()	{
		parent::init();
		
		// Supprime la pagination
		$this->dataProvider->setPagination(false);
		
		// Ecriture des chemins + Urls dans un fichier
		$this->_dumpPaths();
	}
	
	
	

	// _____________________________________________________________________________________________
	/**
	 * Génération du code HTML pour l'affichage des modules.
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::run()
	 * @version 30 avr. 2020	: APE	- Modification des Urls.
	 */
	public function run() {
		// L'URL COURANTE --------------------------------------------------------------------------
		Url::remember(Url::current());
		
		// Le bouton pour plier/déplier les boites.
		$l_STR_BtnPliage 		= Html::tag("span","", ['class'	=> "triangle pull-right glyphicon glyphicon-triangle-bottom"]);
		$l_STR_BtnDelete 		= Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
		$l_STR_BtnModuleActif 	= "";
		$l_STR_BtnModuleDeactif = Html::tag("span", " Désactivé", ["class"=> "badge badge-warning glyphicon glyphicon-ban-circle"]);
		$l_STR_iconDoubleClick	= Html::tag("span", "", ['class'=>"glyphicon glyphicon-edit"]);
		$l_STR_iconDeplacer 	= Html::tag("i", "", ["class" => "glyphicon glyphicon-move"]). " ";
		$l_STR_ZoneDrop 		= Html::tag("div", "Laches moi là", ["class" => "DropZone"]);
		
		// RÉCUPÉRATION DU JEUX DE DONNÉES ---------------------------------------------------------
		// @see https://www.yiiframework.com/doc/guide/2.0/fr/output-data-providers
		$models = array_values($this->dataProvider->getModels());
		$modules = [];
		
		// PARCOURS DE CHACUN DES MODELS
		foreach ($models as $index => $l_OBJ_Module) {
			// FORMATAGE DE LA TRAME (PAYLOAD" ATTENDU POUR CE MODULE ------------------------------
			$formatTrame = [];
			$formatTrameWifi = [];
			
			// L'identification du capteur
			// @see https://www.yiiframework.com/doc/guide/2.0/fr/helper-html
			$formatTrameWifi[] = Html::tag("button ",$l_OBJ_Module->identifiantReseau,["type" => "button", "class" => "btn btn-primary disabled"]);
			//$formatTrameWifiBrute[] = $l_OBJ_Module->identifiantReseau;
			
			// Le séparateur
			$formatTrameWifi[] = Html::tag("button", "/",[	"type" => "button", 
														"class" => "btn btn-primary disabled",
														]);
			
			// BOUTONS D'ÉDITION DU MODULE ---------------------------------------------------------
			$l_TAB_BtnEditionModule 	= [];
			$l_TAB_BtnEditionModule[]	= $this->_btnEdition("/module/update", "glyphicon glyphicon-pencil", $l_OBJ_Module->identifiantReseau);
			$l_TAB_BtnEditionModule[]	= Html::a($l_STR_BtnDelete, ["/module/delete", "id" => $l_OBJ_Module->identifiantReseau],
																	['data-pjax' => "0",
																	"aria-label" => "Supprimer",
																	"title" => "Supprimer",
																	"data-confirm" => "Êtes-vous sûr de vouloir supprimer ce Module ?",
																	"data-method"=>"post"]);
			
			// RECUPERATION DES CAPTEURS RATTACHÉS À CE MODULE -------------------------------------
			$capteurs = [];
			// Recuperation de tout les capteurs de ce module
			$l_TAB_Capteurs = $l_OBJ_Module->relmodulecapteur;
			// Trie de la liste des capteurs selon l'ordre (champ 'ordre' de la table rel_moduleCapteur)
			usort( $l_TAB_Capteurs, function ($a,$b){
				if( $a == $b)	return 0;
				return ($a->ordre < $b->ordre) ? -1 : 1;
			});

			// PARCOURS DE TOUT LES CAPTEURS DU MODULE----------------------------------------------
			$l_TAB_CapteursDuModule =  [];
			$l_TAB_Grandeurs = [];
			foreach( $l_TAB_Capteurs as $l_OBJ_ModuleCapteur)
			{
				// Boutons d'édition du capteur custom
				$l_TAB_BtnCustomCapteur	= [];

				// La customisation de ce capteur pour ce module
				$l_STR_CustomCapteurName	= $l_STR_iconDeplacer.$l_OBJ_ModuleCapteur->nomcapteur;

				// Position du capteur
				$l_STR_Position = $l_OBJ_ModuleCapteur['x']. "," .$l_OBJ_ModuleCapteur['y']. "," .$l_OBJ_ModuleCapteur['z'];
				$l_STR_Position = Html::tag("span", $l_STR_Position, [
																	'class'=>"dblClick alert-secondary",
																	'data' => 
																		[
																		"idModule" 	=> $l_OBJ_ModuleCapteur['idModule'], 
																		"url"		=> Url::to(["/relmodulecapteur/updateajax"]),
																		"idCapteur" => $l_OBJ_ModuleCapteur['idCapteur']
																		]
																	]);
				$l_STR_Position .= " ".$l_STR_iconDoubleClick;
				
				// Ajout de la légende de la position du capteur
				$l_STR_Position	= $this->_legende($l_STR_Position, "Coord.");
				
				// Bouton d'édition du capteur
				$l_TAB_BtnCustomCapteur[]	= $this->_btnEditionCustomCapteur("/relmodulecapteur/update", 
													"glyphicon glyphicon-pencil", 
													$l_OBJ_ModuleCapteur);
				$l_TAB_BtnCustomCapteur[]	= Html::a($l_STR_BtnDelete,
												["/relmodulecapteur/delete", "idModule" => $l_OBJ_ModuleCapteur['idModule'], 
																				"idCapteur" => $l_OBJ_ModuleCapteur['idCapteur'],
																				"nomcapteur" => $l_OBJ_ModuleCapteur['nomcapteur']],
													['data-pjax' => "0",
													"aria-label" => "Supprimer",
													"title" => "Dissocier ce capteur",
													"data-confirm" => "Êtes-vous sûr de vouloir dissocier ce Capteur de ce Module ?",
													"data-method"=>"post"]);

				// Le nom officiel du capteur
				$l_OBJ_Capteur				= Capteur::findOne($l_OBJ_ModuleCapteur->idCapteur0);
				$l_STR_NomCapteur 			= $l_OBJ_ModuleCapteur->nomcapteur;
				$l_TAB_CapteursDuModule[] 	= $l_OBJ_Capteur->nom;
				
				// Contenu de la boite du capteur sur 2 colonnes
				$contents = []; 					
				$contents[] = "<div class='row'>";
				$contents[] = "<div class='col-md-3'>";
				$contents[] = Html::tag("h4", $l_STR_NomCapteur,["class" => "card-title"]);
				$contents[] = $l_STR_Position;
				$contents[] = "</div>";
				$contents[] = "<div class='col-md-9'>";
				$contents[] = "		<div class='row'>";
				
				// Recuperation de chacune des grandeurs rattachées à ce capteur
				foreach( Relcapteurgrandeur::find()->where(["idCapteur" => $l_OBJ_ModuleCapteur->idCapteur])->all() as $l_OBJ_Grandeurs){
					// Formattage des libellés de la grandeur
					$format = $l_OBJ_Grandeurs->idGrandeur0['formatCapteur'];
					$l_STR_Nature		= $this->_toolTip($l_OBJ_Grandeurs->idGrandeur0['nature'], "Nature de la mesure");
					$l_STR_Format		= $this->_toolTip($format, 
															"Format d'encodage de la ".$l_OBJ_Grandeurs->idGrandeur0['nature'].
															" du capteur ".$l_STR_NomCapteur.
															"\nExemple : ".$this->_exempleFormatGrandeur($format));
					//$l_STR_GrandeurID	= $l_OBJ_Grandeurs->idGrandeurs['id'];

					// Récupération de la date de la dernière données entrée dans la table des mesures
					$l_STR_LastDate = $this->_LastDateDataEntry($l_OBJ_Grandeurs->idGrandeur0['tablename']);
					$l_STR_DateLastEntryGrandeur	= $this->_toolTip($l_STR_LastDate, "Date de la dernière données stockée");
					
					// Ajout du format dans la trame
					$formatTrame[] = Html::tag("button ",$l_STR_Format,["type" => "button", "class" => "btn btn-primary disabled"]);

					// Formattage de l'affichage de la grandeur
					$contents[] = Html::tag("div",$l_STR_Nature,["class" => "col-md-5"]);
					$contents[] = Html::tag("div",$l_STR_Format,["class" => "col-md-2"]);
					$contents[] = Html::tag("div",$l_STR_DateLastEntryGrandeur,["class" => "col-md-5"]);

					
					// Enregistrement de la grandeur pour le formattage Python
					$l_TAB_Grandeurs[] = array('format' => $format, 
											'nomCapteur' => $l_STR_NomCapteur,
											'nature' => $l_OBJ_Grandeurs->idGrandeur0['nature']);  
				}
				$contents[] = "		</div>";
				$contents[] = "</div>";
				$contents[] = "</div>";
			
				// Boite autour du capteur
				$capteurs[] = $this->_cardBox([	"header" 	=> $l_STR_CustomCapteurName. " ".implode(" ", $l_TAB_BtnCustomCapteur)." ".$l_STR_BtnPliage,
												"content"	=> implode("", $contents),
												"class"		=> "borderCapteur mb-3 px-0 Capteur",
												"data" 		=> $l_OBJ_ModuleCapteur['idModule']."|".$l_OBJ_ModuleCapteur['idCapteur']."|".$l_OBJ_ModuleCapteur['nomcapteur']."|".$l_OBJ_ModuleCapteur['ordre'],
												"style" 	=> null,
										]);
			}

			// CONSTRUCTION DU CONTENU DU MODULE ---------------------------------------------------
			// Picto si le module est actif ou non
			$l_STR_Actif = ($l_OBJ_Module->actif == 1) ? $l_STR_BtnModuleActif : $l_STR_BtnModuleDeactif;
			// formattage des libellés
			$l_STR_localisationModule	= $this->_toolTip($l_OBJ_Module->localisationModule->description, "Localisation du module");
			$l_STR_Nom 					= $this->_toolTip($l_OBJ_Module->nom, "Nom du module");
			//$l_STR_IdentifiantReseau 	= $this->_toolTip($l_OBJ_Module->identifiantReseau, "Identifiant réseau du module");
			$l_STR_IdentifiantReseau 	= Html::tag("textarea", $l_OBJ_Module->identifiantReseau, ["id" => "identifiantReseau_".$l_OBJ_Module->identifiantReseau,
																						"class" 	=> "TextArea",
																						"data-attribute" 	=> "identifiantReseau",
																						" spellcheck" => "false",
																						"style" => 'resize:none',
																						"rows" => '2',
																						"cols" => '60',
																				]);
			$l_STR_Description			= Html::tag("textarea", $l_OBJ_Module->description, ["id" => "description_".$l_OBJ_Module->identifiantReseau,
																						"class" 	=> "TextArea",
																						"data-attribute" 	=> "description",
																						"spellcheck" => "false",
																						"style" => 'resize:none',
																						"rows" => '3',
																						"cols" => '60',																						
											]);

			
			// Bouton d'ajout d'un capteur
			$l_STR_Icon		= Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]);
			$l_STR_Temp 	= Html::button($l_STR_Icon. " Associer un capteur", ["class" => "button buttonCapteur pull-right btnAjoutCapteur"]);
			$l_STR_BtnAjoutCapteur = Html::a($l_STR_Temp, ['/relmodulecapteur/create', 'idModule' => $l_OBJ_Module['identifiantReseau']], ['class' => 'profile-link']);
			
			
			// Explication de la rêgle du formattage des valeurs
			$l_STR_ReglesFormat	= $this->_legende(tocioRegles::widget(["regle" => "encodageFormatDefinition"]), "Formattage des valeurs");

			// Construction du contenu pour le code python
			$l_STR_CodePython =$this->_cardBox(["header" 	=> '<i class="glyphicon glyphicon-eye-open"></i> Code Python',
					"content"	=> Html::tag("h3","Exemple") 
									.Html::tag("p","Ceci est un exemple de code écrit en Python pour envoyer une Payload formatée dans la base TOCIO.")
									.Html::tag("pre", $this->_codePython( Url::toRoute('/mesure/add/', "https"), $l_OBJ_Module->identifiantReseau, $l_TAB_Grandeurs)),	# Code Python
					"class"		=> "mb-3 px-0 PythonCode",
					"style" 	=> "max-width: 90rem",
			]);

			$host = substr(Url::base(''), 2);

			// Construction de la liste déroulante pour la selection des cartes pour la génération du code Arduino
			//
			//===================================
			//
			//On génère une liste comprennant les noms de toutes les cartes disponible auquelles on associes la valeur de leur ID 
			//On cherche ensuite toutes les méthodes lié à cette carte qui serait compatible avec un capteur de la liste du module
			//On génère ensuite des Input Hidden (un pour chaque carte) qui contiennent toutes les informations
			//pour générer le code des capteurs et qui sera ensuite récupérer par le Java-Script pour générer et afficher le code
			//
			//===================================

			//liste contenant toutes les cartes disponible et une fonction Code Test
			$l_STR_SelectCartes = "";
			//création de la liste
			$l_STR_SelectCartes .= "<select class = 'SelectCartesClass button buttonCarte' id = '". $l_OBJ_Module->identifiantReseau ."SelectCartesId'>";
			$l_STR_SelectCartes .= "  <option value=". $l_OBJ_Module->identifiantReseau .">Select...</option>";	        
	        $l_STR_SelectCartes .= "  <option value=". $l_OBJ_Module->identifiantReseau .">CodeTest</option>";

	        //=====================================================
			//fonction pour ajouter toutes les cartes
			foreach(cartes::find()->all() as $GEN_card_selected)
			{
				$l_STR_SelectCartes .= "  <option value=". $l_OBJ_Module->identifiantReseau .">".$GEN_card_selected['nom']."</option>";
			}
			$l_STR_SelectCartes .= "</select>";

			$l_STR_CardSelectorContent = "";
			$l_STR_CardSelectorContent .= 		'<div class="col-md-3">';
			$l_STR_CardSelectorContent .= 		$l_STR_SelectCartes;
			$l_STR_CardSelectorContent .= 		'</div>';
			$l_STR_CardSelectorContent .= 		'<div class="col-md-9">';
			$l_STR_CardSelectorContent .= 			'<input class="col-md-1 btn bouchonCarteClass" value="'. $l_OBJ_Module->identifiantReseau .'" type="checkbox" id="'. $l_OBJ_Module->identifiantReseau .'bouchon">';
			$l_STR_CardSelectorContent .= 			'<label class="col-md-11">Appliquer un bouchon pour simuler la lecture des données</label>';
			$l_STR_CardSelectorContent .= 		'</div>';
			$l_STR_CardSelectorContent .= 		'<div class="col-md-9">';
			$l_STR_CardSelectorContent .= 			'<input class="col-md-1 btn debugCarteClass" value="'. $l_OBJ_Module->identifiantReseau .'" type="checkbox" id="'. $l_OBJ_Module->identifiantReseau .'debug">';
			$l_STR_CardSelectorContent .= 			'<label class="col-md-11">Afficher les traces de debugs</label>';
			$l_STR_CardSelectorContent .= 		'</div>';

			// Construction de l'afficheur de code (celui-ci est modifiable par le JS pendant l'utilisation)
			$l_STR_GenCodeDisplay =$this->_cardBox(["header" 	=> '<i class="glyphicon glyphicon-eye-open"></i> Code pour Cartes micro-controlleurs',
					"content"	=> $l_STR_CardSelectorContent . Html::tag("pre class='". $l_OBJ_Module->identifiantReseau ."GenCodeDisplay' id = '". $l_OBJ_Module->identifiantReseau ."GenCodeDisplay'", "Aucune carte sélectionné"),
					"class"		=> " mb-3 px-0 PythonCode",
					"style" 	=> "max-width: 90rem",
			]);

			// Construction du contenu pour le code dans un fichier CSV
			$l_STR_CodeCSV =$this->_cardBox(["header" 	=> '<i class="glyphicon glyphicon-eye-open"></i> Formattage d\'un fichier CSV',
					"content"	=> Html::tag("h3","Exemple") 
									.Html::tag("p","Ceci est un exemple de fichier CSV pouvant être importé pour ce module.")
									.Html::tag("p",tocioRegles::widget(["regle" => "fichiercsv"]))
									.Html::tag("p","Ordre des champs :")
									.Html::tag("ol", $this->_codeCSVEntetes($l_OBJ_Module->identifiantReseau, $l_TAB_Grandeurs))
									.Html::tag("p","Exemple d'une ligne au format CSV attendu pour ce Module :")
									.Html::tag("pre", $this->_codeCSVLignes($l_OBJ_Module->identifiantReseau, $l_TAB_Grandeurs)),
					"class"		=> "mb-3 px-0 PythonCode",
					"style" 	=> "max-width: 90rem",
			]);
			
			// Construction du contenu de la boite sur 3 colonnes.
			$l_STR_ModuleActivationStatus = $l_OBJ_Module->actif == "0" ? "checked": "";
			$contents = [];
			$contents[] = "<div class='row'>";
			$contents[] = "<div class='col-md-3'>";
			$contents[] =	 Html::tag("h4", $l_STR_Nom." ". implode(" ", $l_TAB_BtnEditionModule),["class" => "card-title"]);
			$contents[] = 	($l_OBJ_Module->actif == 0) ? Html::tag("div", "Module désactivé", ["class" => "alert alert-dismissible alert-warning text-center"]) : "";
			$contents[] = 	$this->_createSwitchButton(['state' => $l_STR_ModuleActivationStatus, 'label' => "Activé", 'id' => $l_OBJ_Module['identifiantReseau']]);
			$contents[] = 	Html::tag("p", $this->_legende($l_STR_Description, "Description"));
			$contents[] =	Html::tag("p", $this->_legende($l_STR_IdentifiantReseau, "Identifiant réseau"));
			$contents[] = 	Html::tag("p", $this->_legende($l_STR_localisationModule, "Localisation"));
			$contents[] = "</div>";
			$contents[] = "<div class='col-md-9'>";
			$contents[] = "	<div class='row'>";
			$contents[] = "		<div class='col-md-12 Capteurs'>";
			$contents[] = 			implode("", $capteurs);
			$contents[] = "		</div>";
			$contents[] = "	</div>";
			$contents[] = "	<div class='row'>";
			$contents[] = "		<div class='col-md-8 DropZoneContent' data-moduleid='".$l_OBJ_Module->identifiantReseau."'>";
			$contents[] = 		$l_STR_ZoneDrop;
			$contents[] = "		</div>";
			$contents[] = "		<div class='col-md-4'>";
			$contents[] = 		$l_STR_BtnAjoutCapteur;
			$contents[] = "		</div>";
			$contents[] = "	</div>";
			$contents[] = "</div>";
			$contents[] = "<div class='col-md-12'>";
			$contents[] = 	"<fieldset>";
			$contents[] = 		Html::tag("legend", "Format pour transmission Wifi");
			$contents[] = 		"Url pour ajouter les données de ce Module:<br/><code>".Url::toRoute('/mesure/add/[payload]', "https")."</code>";
			$contents[] = 		Html::tag("p", $this->_legende(implode("", $formatTrameWifi).implode("", $formatTrame), "Format attendu de la payload WIFI <span class='TramePayload'></span>"));
			$contents[] = 	"</fieldset>";
			$contents[] = "</div>";
			$contents[] = "<div class='col-md-12'>";
			$contents[] = 	"<fieldset>";
			$contents[] = 		Html::tag("legend", "Exemple de code");
			
			// Select des cartes
			$contents[] = 		$l_STR_GenCodeDisplay;
			

			$contents[] = 		Html::tag("p", $l_STR_CodePython);
			$contents[] = 		Html::tag("p", $l_STR_CodeCSV);
			$contents[] = 	"</fieldset>";
			$contents[] = "</div>";
			$contents[] = "<div class='col-md-12'>";
			$contents[] = 	"<fieldset>";
			$contents[] = 		Html::tag("legend", "Format pour transmission Lora");
			$contents[] = 		"Url pour ajouter les données de ce Module (à mettre dans le Back Office de Orange):<br/><code>".Url::toRoute('/mesure/addlora', "https")."</code>";
			$contents[] = 		Html::tag("p", $this->_legende(implode("", $formatTrame), "Format attendu de la payload LORA <span class='TramePayload'></span>"));
			$contents[] = 	"</fieldset>";
			$contents[] = "</div>";
			$contents[] = "<div class='col-md-12'>";
			$contents[] = $l_STR_ReglesFormat;
			$contents[] = "</div>";
			$contents[] = "</div>";
			
			
			
			
			// CONSTRUCTION DE LA BOITE DU MODULE --------------------------------------------------
			$modules[] = $this->_cardBox(["header" 	=> $l_STR_Actif." ".$l_STR_Nom." (".implode(" + ", $l_TAB_CapteursDuModule).") ".$l_STR_BtnPliage,
											"titre" 	=> $l_STR_Description,
											"content"	=> implode("", $contents),
											"id"		=> $l_OBJ_Module->identifiantReseau,
											"class"		=> "card borderModule  mb-3 px-0 Module",
											"data"		=> $l_OBJ_Module->identifiantReseau ,
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
	 * 		id : l'ID de la div contennant le body 
	 * 		data : si présent, la valeur du data-id mis dans le card
	 * 
	 * 
	 * @see https://bootswatch.com/slate/
	 * @param array $params tableau de la forme cle, valeur.
	 * @return string HTML.
	 * 
	 * 	@version 16 avr. 2020	: APE	- Encodage des paramètres affichées dans le data.
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
		
		// le BODY du card
		if( isset($params['id'])) {
			$body	= Html::tag("div", $titre . $text, array("class" => "card-body", 'id' => $params['id']));
		} else {
			$body	= Html::tag("div", $titre . $text, array("class" => "card-body"));
			
		}
		
		
		// BOITE
		$class 	= ($params['class'] !== null ) ? $params['class'] : "";
		$style 	= ($params['style'] !== null ) ? $params['style'] : "";
		//si on a un data
		if( isset( $params['data'])){
			$cardBox = Html::tag("div",$header . $body , array("class" => "card  ".$class,
																"style" => $style,
																"data-value"=> Html::encode($params['data']),
																));
			
		} else {
			$cardBox = Html::tag("div",$header . $body , array("class" => "card  ".$class,
															"style" => $style,
															));
		}
		// AFFICHAGE DE LA BOITE
		return $cardBox;
	}
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Retourne un exemeple de formattage de grandeur en fonctoin du format fourni en paramètre.
	 * @param string $format le format tel que défini dans le BDD pour la grandeur.
	 * @return string
	 * 	@version 3 juil. 2020	: APE	- Transformation du formattage de la grandeur avec un point et non plus une virgule.
	 */
	private function _exempleFormatGrandeur($format){
		// Extraction de la partie avant et apres le point
		list($l_STR_Avant, $l_INT_Apres) = explode(".", $format);
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
	 * Renvoie la date de la dernière entrée dans la table des mesures pour cette grandeur.
	 * @param string $p_STR_TableName Le nomde la tables des mesures.
	 * @return array date
	 */
	private function _LastDateDataEntry($p_STR_TableName){
		
		$row = (new \yii\db\Query())
				->select(['max(timestamp) as lastDate'])
				->from($p_STR_TableName)
				->one();
		if( $row['lastDate'] != null ) {
			return date("d/m/Y H:i:s", strtotime($row['lastDate']));
			
		} else {
			return "No data";
		}
	}
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Génère le code HTML pour un bouton d'édition.
	 * @param string $link 	à déclancher lorsque l'on clique sur le bouton
	 * @param string $icon	du bouton.
	 * @param objet  $p_OBJ_Module l'objet contennat le modèle du Module.
	 * @return string
	 */
	private function _btnEditionCustomCapteur($link,$icon, $p_OBJ_Module){
		$btn = Html::tag("span ", "",["class" => $icon]);
		return Html::a($btn, [$link, 'idModule' => $p_OBJ_Module->idModule,
				'idCapteur' => $p_OBJ_Module->idCapteur, 
				'nomcapteur' => $p_OBJ_Module->nomcapteur,
				'ordre' => $p_OBJ_Module->ordre, 
		]);
	}
	
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Ecriture des chemins pour les URls dans un fichier lisible par le JavaScript. 
	 * @version 11 mai 2020	: APE	- Changement de protocol utilisé pour l'url de base.
	 */
	private function _dumpPaths()
	{
		// Génération du contenu
		if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') || (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) 
		{
		    $server_request_scheme = 'https';
		}
		else 
		{
		    $server_request_scheme = 'http';
		}

		$l_TAB_Content = [];
		$l_TAB_Content[] = "// Ceci est un fichier généré dynamiquement par le script :\n// ".__FILE__;
		$l_TAB_Content[] = "// ---------------------------------------------------------------------";
		$l_TAB_Content[] = "// NE PAS MODIFIER CE FICHIER À LA MAIN";
		$l_TAB_Content[] = "// ---------------------------------------------------------------------";
		$l_TAB_Content[] = "var g_urlbehindproxy = '".\Yii::getAlias("@urlbehindproxy")."/';	// Set in /config/web.php";
		$l_TAB_Content[] = "var g_host = '".Url::base($server_request_scheme)."';";
		
		
		
		
		
		// Ecriture du fichier
		$l_STR_FileName = \Yii::getAlias('@webroot/assets/config.js');
		$l_HDL_File 	= fopen( $l_STR_FileName,"w");
		fwrite($l_HDL_File, implode("\n", $l_TAB_Content));
		
		fclose( $l_HDL_File );
	}

	// _____________________________________________________________________________________________
	/**
	 * Give HTML code for a switch button on the label $params['label'] with id $param['id'] and 
	 * state (checked) $param['state']
	 * 
	 * $param['id'] = the module id
	 * $param['label'] = the string just behind the switch button
	 * $param['state'] = "checkeed" or null
	 *
	 * @param array $params id, label, state
	 * @return string with HTML code ready to display.
	 */
	private function _createSwitchButton( $params) {
		$l_STR_Id = isset( $params['id'] ) ? $params['id'] : "name_" . date( "Ymd_His", time() );
		$l_STR_Label = isset( $params['label'] ) ? $params['label'] : "";
		$l_STR_State = isset( $params['state'] ) ? $params['state'] : "";

		return '<div class="form-group">
					<div class="custom-control custom-switch">
						<input type="checkbox" 
							class="warning custom-control-input switchToogle" 
							data-moduleid="'.$l_STR_Id.'" 
							id="btt_' . $l_STR_Id.'" '.$l_STR_State.'>
						<label class="custom-control-label" for="btt_'.$l_STR_Id.'">'.$l_STR_Label.'</label>
					</div>
				</div>';
	}
	
	
	// ---------------------------------------------------------------------------------------------
	/**
	 * Supprimer tout les accents dans une chaine de caractère.
	 * @param $string à nettoyer
	 * @return string sans accents.
	 * @version 9 déc. 2020	: APE	- Modification.
	 */
	private function _stripAccents($string){
		$accent = array('%C3%80'=>'A','%C3%81'=>'A','%C3%82'=>'A','%C3%83'=>'A','%C3%84'=>'A','%C3%85'=>'A','%C3%A0'=>'a','%C3%A1'=>'a','%C3%A2'=>'a','%C3%A3'=>'a','%C3%A4'=>'a','%C3%A5'=>'a','%C3%92'=>'O','%C3%93'=>'O','%C3%94'=>'O','%C3%95'=>'O','%C3%96'=>'O','%C3%98'=>'O','%C3%B2'=>'o','%C3%B3'=>'o','%C3%B4'=>'o','%C3%B5'=>'o','%C3%B6'=>'o','%C3%B8'=>'o','%C3%88'=>'E','%C3%89'=>'E','%C3%8A'=>'E','%C3%8B'=>'E','%C3%A8'=>'e','%C3%A9'=>'e','%C3%AA'=>'e','%C3%AB'=>'e','%C3%87'=>'C','%C3%A7'=>'c','%C3%8C'=>'I','%C3%8D'=>'I','%C3%8E'=>'I','%C3%8F'=>'I','%C3%AC'=>'i','%C3%AD'=>'i','%C3%AE'=>'i','%C3%AF'=>'i','%C3%99'=>'U','%C3%9A'=>'U','%C3%9B'=>'U','%C3%9C'=>'U','%C3%B9'=>'u','%C3%BA'=>'u','%C3%BB'=>'u','%C3%BC'=>'u','%C3%BF'=>'y','%C3%91'=>'N','%C3%B1'=>'n');
		$chaine = urlencode($string);
		foreach ($accent as $key => $value) {
			$chaine = str_replace($key,$value,$chaine);
		}
		return urldecode($chaine);
	}
	
	
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Renvoie le formattage qu'il faut à la valeur Python en fonction de la chaine envoyée.
	 * @param string $format
	 * @return string
	 */
	private function _codePythonFormatChaine($format){
		# test l'existance du signe
		$existeSigne = strpos($format, "-");
		
		# Récupération de ce qui est avant et apres le point
		list($avant,$apres) = explode(".", $format);
		
		# Suppression du signe
		$avant	= str_replace("-", "", $avant);
		
		# Calcul du nombre de caractères
		$longeur = $avant + $apres;
		if( $existeSigne !== false )	$longeur ++;

		# Si on doit avoir un signe
		if( $existeSigne !== false){
			return "{:+0".($longeur +1) .".".$apres."f}";
		} else {
			return "{:0".$longeur.".".$apres."f}";
		}
	}
	

	
	// _____________________________________________________________________________________________
	/**
	 * Formattage de code Python pour envoyer la trame WIFI.
	 * 
	 * @param string $url : URL sur laquelle envoyer la payload.
	 * @param string $id : l'ID du module (son identifiant réseau)
	 * @param array $params : tableau contenant les grandeurs à envoyer dans la payload. Tableau indexé sous la forme ['nature' => ....., 'format'=> ....]
	 * @return string
	 * 
	 * 	@version 12 févr. 2021	: APE	- protection contre les noms de Granndeur n'ayant pas d'espaces.
	 */
	private function _codePython($url, $id, $params ){
		$ligne = [];
		
		$format = [];
		$natures = [];
		$compteur = 0;
		foreach ( $params as $grandeur ){
			// Si le nom de la Grandeur a pas un espace
			if( stripos( $grandeur['nature']," ") !== false){
				list($nature, $null) = explode(" ", $grandeur['nature']);
			} else {
				$nature = $grandeur['nature'];
			}
			$natureValue = $nature.$compteur++;
			$variable = strtolower($this->_stripAccents($natureValue));
			$natures[] = $variable;
			
			$format[] = $this->_codePythonFormatChaine($grandeur['format']);
			$ligne[] = "# ".strtolower($this->_stripAccents($natureValue))." is the '".$nature."' value from your sensor '".$grandeur['nomCapteur']."' (as float)";
			$ligne[] = $variable." = 0000.00 # <- Your code to read value from sensor goes here ";
			$ligne[] = "";
		}

		$ligne[] = 'payload = "'.implode("", $format).'".format('.implode(",", $natures).')';
		$ligne[] = 'url = "'.$url.'/'.$id.'/"';
		$ligne[] = 'url = url + payload.replace(".", "")';
		$ligne[] = '';
		$ligne[] = '# Send the request with GET method';
		$ligne[] = 'response = requests.get(url)';
		$ligne[] = '';
		$ligne[] = '# Just for debug :-)';
		$ligne[] = 'print( url, response.json() )';
		return implode("<br/>",$ligne);
	}

	// _____________________________________________________________________________________________
	/**
	 * Entete de fichier pour Formattage d'un fichier journal au format CSV.
	 *
	 * @param string $id : l'ID du module (son identifiant réseau)
	 * @param array $params : tableau contenant les grandeurs à envoyer dans la payload. Tableau indexé sous la forme ['nature' => ....., 'format'=> ....]
	 * @return string
	 *
	 *	@version 3 mars 2021	: APE	- Création.
	 */
	private function _codeCSVEntetes($id, $params ){
		$ligne 		= [];
		$natures 	= [];
		$compteur 	= 0;
		
		$ligne[] = '"Identifiant du Module";';
		$ligne[] = '"Timestamp de la mesure";';
		
		foreach ( $params as $grandeur ){
			// Si le nom de la Grandeur n'a pas d'espace
			if( stripos( $grandeur['nature']," ") !== false){
				list($nature, $null) = explode(" ", $grandeur['nature']);
			} else {
				$nature = $grandeur['nature'];
			}
			
			$ligne[] = "\"".$nature." (value from your sensor '".$grandeur['nomCapteur']."')\";";
		}
		
		return "<li>".implode("</li><li>",$ligne)."</li>";
	}
	// _____________________________________________________________________________________________
	/**
	 * Exemple de formattage d'une ligne pour un fichier journal au format CSV.
	 *
	 * @param string $id : l'ID du module (son identifiant réseau)
	 * @param array $params : tableau contenant les grandeurs à envoyer dans la payload. Tableau indexé sous la forme ['nature' => ....., 'format'=> ....]
	 * @return string
	 *
	 *	@version 3 mars 2021	: APE	- Création.
	 */
	private function _codeCSVLignes($id, $params ){
		$ligne 		= [];
		$champs		= [];
		
		$champs[] 	= "\"".$id."\"";
		$champs[]	= "\"". time()."\"";
		
		// Construction des champs des mesures dans l'ordre imposé.
		foreach ( $params as $grandeur ){
			
			$champs[] = "\"".$this->_exempleFormatGrandeur($grandeur['format'])."\"";
		}
		
		// Creation de lignes contenant les champs concaténés avec un ;
		$ligne[]	= implode(";", $champs);
		
		
		return implode("<br/>",$ligne);
	}
}
?>