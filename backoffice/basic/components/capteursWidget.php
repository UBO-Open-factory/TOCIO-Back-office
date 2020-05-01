<?php
namespace app\components;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\VarDumper;
use app\models\Relcapteurgrandeur;
use app\models\Relmodulecapteur;
use app\models\Capteur;
use yii\helpers\Url;

/**
 * Widget pour la page des Capteurs.
 * 
 * @file capteursWidget.php
 * @author : Alexandre PERETJATKO (APE)
 * @version 16 avr. 2020	: APE	- Création.
 *
 */
class capteursWidget extends Widget
{
	public $dataProvider;
	
	
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::init()
	 */
	public function init()	{
		parent::init();
		$this->_dumpPaths();
	}
	

	// _____________________________________________________________________________________________
	/**
	 * Génération du code HTML pour l'affichage de la liste des capteurs.
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::run()
	 */
	public function run() {
		// Le bouton pour plier/déplier les boites.
		$l_STR_BtnDelete 		= Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
		$l_STR_iconDeplacer 	= Html::tag("i", "", ["class" => "glyphicon glyphicon-move"]). " ";
		$capteurs = [];
		
		
		// RÉCUPÉRATION DU JEUX DE DONNÉES ---------------------------------------------------------
		// @see https://www.yiiframework.com/doc/guide/2.0/fr/output-data-providers
		if(isset($this->dataProvider)) {
			$models = array_values($this->dataProvider->getModels());
		} else {
			$models = $this->data;
		}


		

		// PARCOURS DE CHACUN DES MODELS (DES CAPTEURS) --------------------------------------------
		foreach ($models as $l_OBJ_Capteur) {
			// EXTRACTION DES DONNÉES DU CAPTEUR
			// On peut avoir :
			// - un objet quand le dataprovider est celui de Yii (cas de la page capteur/index)
			// - un tableau quand le dataprovider est le résultat d'une requète SQL ( cas de module/index)
			if( is_object( $l_OBJ_Capteur )) {
				$l_STR_IDCapteur 	= $l_OBJ_Capteur->id;
				$l_STR_NomCapteur 	= $l_OBJ_Capteur->nom;
			} else {
				$l_STR_IDCapteur 	= $l_OBJ_Capteur['id'];
				$l_STR_NomCapteur 	= $l_OBJ_Capteur['nom'];
			}
			
			
			// BOUTONS D'ÉDITION DU CAPTEUR --------------------------------------------------------
			$l_TAB_BtnEditionCapteur 	= [];
			$l_TAB_BtnEditionCapteur[]	= $this->_btnEdition("capteur/update", "glyphicon glyphicon-pencil", $l_STR_IDCapteur);
			$l_TAB_BtnEditionCapteur[]	= Html::a($l_STR_BtnDelete, ["capteur/delete", "id" => $l_STR_IDCapteur],
																	['data-pjax' => "0",
																	"aria-label" => "Supprimer",
																	"title" => "Supprimer",
																	"data-confirm" => "Êtes-vous sûr de vouloir supprimer ce Capteur ?",
																	"data-method"=>"post"]);
			
		
			

		
			// Contenu de la boite du capteur
			$contents = [];
			$contents[] = "<div class='row'>";
			
			
			// RECUPERATION DE CHACUNE DES GRANDEURS RATTACHÉES À CE CAPTEUR -----------------------
			foreach( Relcapteurgrandeur::find()->where(["idCapteur" => $l_STR_IDCapteur])->all() as $l_OBJ_Grandeurs){
				// Formattage des libellés de la grandeur
				$format = $l_OBJ_Grandeurs->idGrandeur0['formatCapteur'];
				$l_STR_Nature		= $this->_toolTip($l_OBJ_Grandeurs->idGrandeur0['nature'], "Nature de la mesure");
				$l_STR_Format		= $this->_toolTip($format, "Format d'encodage de la ".$l_OBJ_Grandeurs->idGrandeur0['nature'].
														" du capteur ".$l_STR_NomCapteur.
														"\nExemple : ".$this->_exempleFormatGrandeur($format));

				
				
				// Formattage de l'affichage de la grandeur
				$contents[] = Html::tag("div",
										$l_STR_Nature,
										["class" => "col-md-8"]);
				$contents[] = Html::tag("div",
										$l_STR_Format,
										["class" => "col-md-4"]);
			}
			$contents[] = "</div>";
			
			
			
			// BOITE DU CAPTEUR
			$capteurs[] = $this->_cardBox([	"header" 	=> $l_STR_iconDeplacer.$l_STR_NomCapteur. Html::tag("span",implode(" ", $l_TAB_BtnEditionCapteur),['class' => "pull-right"]),
											"content"	=> implode("", $contents),
											"class"		=> "border-info mb-3 px-0 CapteurOriginal",
											"data" 		=> $l_STR_NomCapteur."|".$l_STR_IDCapteur,
											"style" 	=> null,
									]);

		}
		
		
		// Bouton d'ajout d'un capteur
		$l_STR_Icon		= Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]);
		$l_STR_Temp 	= Html::button($l_STR_Icon. " Ajouter un Capteur", ["class" => "btn btn-info pull-right"]);
		$l_STR_BtnAjoutCapteur = Html::a($l_STR_Temp, ['capteur/create'], ['class' => 'profile-link']);
		
		
		// AFFICHAGE DE LA LISTE DES CAPTEURS ------------------------------------------------------
		return implode("", $capteurs);
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
	 * 		data : si présent, la valeur du data-id mis dans le card
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
		//si on a un data
		if( isset( $params['data'])){
			$cardBox = Html::tag("div",$header . $body , array("class" => "card  ".$class,
																"style" => $style,
																"data-value"=> $params['data'],
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
	 * Ecriture des chemins pour les URls dans un fichier lisible par le JavaScript.
	 */
	private function _dumpPaths(){
		// Génération du contenu
		$l_TAB_Content = [];
		$l_TAB_Content[] = "// Ceci est un fichier généré dynamiquement par le script ".__FILE__;
		$l_TAB_Content[] = "// Ne pas le modifier à la main";
		$l_TAB_Content[] = "var g_urlbehindproxy = '".\Yii::getAlias("@urlbehindproxy")."/';	// Set in /config/web.php";
		$l_TAB_Content[] = "var g_host = '".Url::base('https')."';";
		
		
		
		
		
		// Ecriture du fichier
		$l_STR_FileName = \Yii::getAlias('@webroot/assets/capteur/config.js');
		$l_HDL_File 	= fopen( $l_STR_FileName,"w");
		fwrite($l_HDL_File, implode("\n", $l_TAB_Content));
		
		fclose( $l_HDL_File );
	}
}
?>
	