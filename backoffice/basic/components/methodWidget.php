<?php
namespace app\components;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\VarDumper;
use app\models\Relmethodgrandeur;
use app\models\Relmodulemethod;
use app\models\Method;
use app\models\Capteur;
use app\models\Relcapteurgrandeur;
use app\models\Grandeur;
use yii\helpers\Url;

/**
 * Widget pour la page des methods.
 * 
 * @file methodsWidget.php
 * @author : Kilian LE GALL (APE)
 * @version 11 avr. 2020	: APE	- Création.
 *
 */
class methodWidget extends Widget
{
	public $dataProvider;
	
	
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::init()
	 */
	public function init()	
	{
		parent::init();
	}
	

	// _____________________________________________________________________________________________
	/**
	 * Génération du code HTML pour l'affichage de la liste des methods.
	 * 
	 * {@inheritDoc}
	 * @see \yii\base\Widget::run()
	 */
	public function run() 
	{
		Url::remember();
		// Le bouton pour plier/déplier les boites.
		$l_STR_BtnPliage 		= Html::tag("span","", ['class'	=> "triangle pull-right glyphicon glyphicon-triangle-bottom"]);
		$l_STR_BtnDelete 		= Html::tag("span", "", ["class" => "glyphicon glyphicon-trash"]);
		$l_STR_iconDeplacer 	= Html::tag("i", "", ["class" => "glyphicon glyphicon-move"]). " ";
		$methods = [];
		
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

		// PARCOURS DE CHACUN DES MODELS (DES methods) --------------------------------------------
		foreach ($models as $l_OBJ_method) 
		{
			// EXTRACTION DES DONNÉES DU method
			// On peut avoir :
			// - un objet quand le dataprovider est celui de Yii (cas de la page method/index)
			// - un tableau quand le dataprovider est le résultat d'une requète SQL ( cas de module/index)
			if( is_object( $l_OBJ_method )) 
			{
				$l_STR_IDmethod 	= $l_OBJ_method->id;
				$l_STR_nom_methodmethod 	= $l_OBJ_method->nom_method;
			} 
			else 
			{
				$l_STR_IDmethod 	= $l_OBJ_method['id'];
				$l_STR_nom_methodmethod 	= $l_OBJ_method['nom_method'];
			}
			
			// BOUTONS D'ÉDITION DU method --------------------------------------------------------
			$l_TAB_BtnEditionmethod 	= [];
			$l_TAB_BtnEditionmethod[]	= $this->_btnEdition("method/update", "glyphicon glyphicon-pencil", $l_STR_IDmethod);
			$l_TAB_BtnEditionmethod[]	= Html::a($l_STR_BtnDelete, ["method/delete", "id" => $l_STR_IDmethod],
																	['data-pjax' => "0",
																	"aria-label" => "Supprimer",
																	"title" => "Supprimer",
																	"data-confirm" => "Êtes-vous sûr de vouloir supprimer ce method ?",
																	"data-method"=>"post"]);
						
			// Contenu de la boite de la method
			$contents = [];
			$contents[] = "<div class='row'>";
			// RECUPERATION DE CHACUNE DES GRANDEURS RATTACHÉES À CETTE METHOD -----------------------
			//création des boites de méthodes 

			//définition des paramètres qui s'appliqueront à toutes les textesbox
			$param_textbox = " spellcheck='false' style='resize:none;' ";
			foreach( method::find()->where(["id" => $l_STR_IDmethod])->all() as $l_OBJ_method)
			{
				$methode_lecture = explode("|CutBalise|",$l_OBJ_method['method_read']);
				//création des champs de textes simples, pour les include , statement , setup (ils n'apparraissent qu'une fois par méthode et n'ont qu'un champ unique)
				$contents[] = 	"<div class='col-sm-12'>";
				$contents[] = 		"<label>CAPTEUR : <h2>" . capteur::find(['nom'])->where(['id' => $l_OBJ_method['id_capteur']])->one()['nom'] . " </h></label><br> ";
				$contents[] = 	"</div><br><br><br>";
				$contents[] = 	"<div class='col-sm-5'>";
				$contents[] = 		"<label>Ligne d'INCLUDE</label><br> ";
				$contents[] = 		"<textarea rows='1' cols='40'" . $param_textbox . ">" .$l_OBJ_method['method_include'] . " </textarea> ";
				$contents[] = 		"<label>Ligne de DECLARATION</label><br> ";
				$contents[] = 		"<textarea rows='2' cols='40'" . $param_textbox . ">" .$l_OBJ_method['method_statement']. " </textarea> ";
				$contents[] = 		"<label>Ligne d'INITIALISATION</label><br> ";
				$contents[] = 		"<textarea rows='2' cols='40'" . $param_textbox . ">" .$l_OBJ_method['method_setup']. " </textarea> ";
				$contents[] = 	"</div>";

				$contents[] = 	"<div class='col-sm-7'>";
				$contents[] = 		"<label><h4>Code de lecture des données</h4></label><br> ";

				//=======================================
				//on sépare le champ principale (read) en champs différés , un par grandeur, et on entre , dans l'ordre les valeurs de la colonne read
				//ordre des requetes 
				//	nom du capteur de la méthode => donne => l'id du capteur
				//	l'id du capteur concerné => donne => les id des grandeurs concerné
				//	les id des grandeurs => donnent => leurs noms

				$i = 0;
				foreach (relcapteurgrandeur::find()->where(["idCapteur" => $l_OBJ_method['id_capteur']])->all() 
				as $id_relgrandeur) 
				{					
					foreach (grandeur::find()->where(["id" => $id_relgrandeur["idGrandeur"]])->all() 
					as $nom_grandeur) 
					{
						$contents[] = "<label>" . $nom_grandeur['nature'] . "</label><br> ";
					}
					if(count($methode_lecture)-1>$i)
					{
						$contents[] = "<textarea rows='2' cols='70'" . $param_textbox . ">" . $methode_lecture[$i] ."</textarea> ";
					}
					else
					{
						$contents[] = "<textarea rows='2' cols='70'" . $param_textbox . "></textarea> ";
					}
					$contents[] = "<br>";
					$i++;
				}
				$contents[] = 	"</div>";
			}
			$contents[] = "</div>";
			
			// BOITE DU method
			$methods[] = $this->_cardBox([	"header" 	=> $l_STR_nom_methodmethod . $l_STR_BtnPliage,
											"content"	=> Html::tag("span",implode(" ", $l_TAB_BtnEditionmethod),['class' => "pull-right"]) . implode("", $contents),
											"class"		=> "border-info mb-3 px-0 methodOriginal",
											"data" 		=> $l_STR_nom_methodmethod."|".$l_STR_IDmethod,
											"style" 	=> null,
									]);
		}
		
		// Bouton d'ajout d'un method
		$l_STR_Icon		= Html::tag("span", "", ["class" => "glyphicon glyphicon-plus"]);
		$l_STR_Temp 	= Html::button($l_STR_Icon. " Ajouter un method", ["class" => "button buttonMethod pull-right"]);
		$l_STR_BtnAjoutmethod = Html::a($l_STR_Temp, ['method/create'], ['class' => 'button buttonMethod profile-link']);
		
		// AFFICHAGE DE LA LISTE DES methods ------------------------------------------------------
		return implode("", $methods);
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
	private function _cardBox($params) 
	{
		// ENTETE		
		$header 	= ($params['header'] !== null) ? Html::tag("div", $params['header'], array("class" => "card-header toggleAffichage")):"";
		
		// CONTENU
		if( !isset($params['content']) )
		{
			$titre 	= ($params['titre'] !== null) ? Html::tag("h4",$params['titre'], array("class" => "card-title") ):"";
			$text	= ($params['text'] !== null) ? Html::tag('p', $params['text'], array("class"=>"card-text")):"";
		} 
		else 
		{
			$titre = "";
			$text = $params['content'];
		}
		
		// le BODY du card
		if( isset($params['id'])) 
		{
			$body	= Html::tag("div", $titre . $text, array("class" => "card-body", 'id' => $params['id']));
		} 
		else 
		{
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
			
		} 
		else 
		{
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
	 * 	@version 3 juil. 2020	: APE	- Le formattage de la Grandeur est maintenant un point et 
	 * 										non plus une virgule.
	 */
	private function _exempleFormatGrandeur($format)
	{
		// Extraction de la partie avant et apres le point
		list($l_STR_Avant, $l_INT_Apres) = explode(".", $format);
		$l_INT_Avant = abs($l_STR_Avant);	// On prend la valeur absolue de ce qui est avant la virgule.

		
		// Si ce qui est avant la virgule doit contenir un signe
		$l_STR_Signe = "";
		if( strpos($l_STR_Avant, "-") !== false )
		{
			$l_STR_Signe = "-";
		}
		
		$l_TAB_Avant = [];
		for( $i=0; $i < $l_INT_Avant; $i++)
		{
			$l_TAB_Avant[] = $i;
		}
		$l_TAB_Apres= [];
		
		for( $i=0; $i < $l_INT_Apres; $i++)
		{
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
	private function _toolTip($mot, $legend)
	{
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
	private function _btnEdition($link,$icon, $id)
	{
		$btn = Html::tag("span ", "",["class" => $icon]);
		return Html::a($btn, [$link, 'id' => $id]);
	}
}
?>
