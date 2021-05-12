<?php
namespace app\components;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\VarDumper;
use app\models\Relcartesmethod;
use app\models\Relmodulecarte;
use app\models\carte;
use yii\helpers\Url;
use app\models\Method;
/**
 * Widget pour la page des cartes.
 * 
 * @file cartesWidget.php
 * @author : Alexandre PERETJATKO (APE)
 * @version 16 avr. 2020	: APE	- Création.
 *
 */
class cartesWidget extends Widget
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
	 * Génération du code HTML pour l'affichage de la liste des cartes.
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::run()
	 */
	public function run() 
	{			
		// Le bouton pour plier/déplier les boites.
		$l_STR_BtnDelete 		= Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
		$l_STR_iconDeplacer 	= Html::tag("i", "", ["class" => "glyphicon glyphicon-move"]). " ";
		$cartes = [];

		// RÉCUPÉRATION DU JEUX DE DONNÉES ---------------------------------------------------------
		// @see https://www.yiiframework.com/doc/guide/2.0/fr/output-data-providers
		if(isset($this->dataProvider)) 
		{
			$models = array_values($this->dataProvider->getModels());
		} 
		else 
		{
			$models = $this->data;
		}

		// PARCOURS DE CHACUN DES MODELS (DES CARTES) --------------------------------------------
		foreach ($models as $l_OBJ_CARTE) 
		{
			// EXTRACTION DES DONNÉES DU method
			// On peut avoir :
			// - un objet quand le dataprovider est celui de Yii (cas de la page method/index)
			// - un tableau quand le dataprovider est le résultat d'une requète SQL ( cas de module/index)
			if( is_object( $l_OBJ_CARTE )) 
			{
				$l_STR_ID_CARTE = $l_OBJ_CARTE->id;
				$l_STR_nom_CARTE 	= $l_OBJ_CARTE->nom;
			} 
			else 
			{
				$l_STR_ID_CARTE = $l_OBJ_CARTE['id'];
				$l_STR_NOM_CARTE 	= $l_OBJ_CARTE['nom'];
			}
			// BOUTONS D'ÉDITION DE LA CARTE --------------------------------------------------------
			$l_TAB_BtnEditiocarte = [];
			$l_TAB_BtnEditiocarte[] = $this->_btnEdition("cartes/update", "glyphicon glyphicon-pencil", $l_STR_ID_CARTE);
			$l_TAB_BtnEditiocarte[] = Html::a($l_STR_BtnDelete, 
				[
				"cartes/delete", "id" => $l_STR_ID_CARTE
				],
				[
				'data-pjax' => "0",
				"aria-label" => "Supprimer",
				"title" => "Supprimer",
				"data-confirm" => "Êtes-vous sûr de vouloir supprimer cette carte ?",
				"data-method"=>"post"
				]
			);

			$contents = [];
			$contents[] = "<div class='row'>";
			$contents[] = "<legend class='col'> Nom method</legend>";
			$contents[] = "<legend class='col'> Capteur associé</legend>";
			$contents[] = "<legend class='col-1'> </legend>";
			$contents[] = "<legend class='col-2'> </legend>";
			$contents[] = "<legend class='col-1'> </legend>";
			foreach(relcartesmethod::find()->where(["id_carte" => $l_STR_ID_CARTE])->all() as $method)
			{
				$l_STR_BtnWarning = Html::tag("span", "", ["class" => "glyphicon glyphicon-exclamation-sign"]);
				$l_STR_BtnWarning = Html::a($l_STR_BtnWarning,
																[
																	"",
																],
	 				  				         					[
		 		  				         							"aria-label" => "Warning",
		 		  				         							"title" => "Warning",
		 		  				         							"data-confirm" => "Cette méthode n'a pas été initialement conçu pour cette carte",
				  				         							"data-method"=>"post"
			  				         							]);

				$l_STR_BtnModify = Html::tag("span", "", ["class" => "glyphicon glyphicon-cog "]);
				$l_STR_BtnModify = Html::a($l_STR_BtnModify,	
																[
	 		  				         								"/method/update",
 		  				         									"id" => $method['id_method']
 		  				         								],
	 				  				         					[
	 				  				         						'data-pjax' => "0",
		 		  				         							"aria-label" => "modifier",
		 		  				         							"title" => "modifier",
				  				         							"data-method"=>"post"
			  				         							]);

				$l_STR_BtnDelete = Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
				$contents[] = "<div class='col-sm-12'>";	
				$contents[] = "<div class='row'>";
				$contents[] = 	"<div class='col'>" . method::find()->where(["id" => $method["id_method"]])->one()["nom_method"] . "</div>";
				$contents[] = 	"<div class='col'>" . explode("_",method::find()->where(["id" => $method["id_method"]])->one()["nom_method"])[0] . "</div>";
				if( !in_array(str_replace(" ","",$l_OBJ_CARTE['nom']),explode("_",method::find()->where(["id" => $method["id_method"]])->one()["nom_method"])))
				{
				$contents[] = 	"<div class='col-2'>" . $l_STR_BtnWarning . "<h8 style='color:orange'> Warning</h></div>";
				}
				else
				{
				$contents[] = 	"<div class='col-2'> </div>";
				}
				
				$contents[] = "	<div class='col-2'>" . $l_STR_BtnModify . "</div>";
				$contents[] = "</div>";
				$contents[] = "</div>";
				
			}
			$contents[] = "</div>";

			// BOITE DU method
			$carte[] = $this->_cardBox([	"header" 	=> $l_STR_iconDeplacer.$l_STR_nom_CARTE. Html::tag("span",implode(" ", $l_TAB_BtnEditiocarte),['class' => "pull-right"]),
											"content"	=> implode("", $contents),
											"class"		=> "border-info mb-3 px-0 methodOriginal",
											"data" 		=> $l_STR_nom_CARTE."|".$l_STR_ID_CARTE,
											"style" 	=> null,
									]);
		}
		// Bouton d'ajout d'un method
		$l_STR_Icon		= Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]);
		$l_STR_Temp 	= Html::button($l_STR_Icon. " Ajouter une carte", ["class" => "btn btn-info pull-right"]);
		$l_STR_BtnAjoutcarte = Html::a($l_STR_Temp, ['carte/create'], ['class' => 'profile-link']);
		
		// AFFICHAGE DE LA LISTE DES methods ------------------------------------------------------
		return implode("", $carte);

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
}
?>
	