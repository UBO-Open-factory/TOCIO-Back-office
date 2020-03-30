<?php
namespace app\controllers;
use Yii;
use app\models\Module;
use app\models\Capteur;
use app\models\Grandeur;
use yii\db\Query;
use yii\debug\models\search\Debug;


/**
 * Controlleur permettant d'ajouter une mesure dans la base.
 * 
 * @file MesureController.php 
 * @author Alex
 */
class MesureController extends \yii\web\Controller {
	
	
	//==============================================================================================
	/**
	 * Renvoie les 100 dernières mesures d'un modules.
	 */
	public function actionGet(){
		// RECUPERATION DU PARAMETRE PASSÉ EN GET --------------------------------------------------
		// @see https://www.yiiframework.com/doc/guide/2.0/fr/runtime-requests
		$request = Yii::$app->request;
		
		$get	= $request->get();
		$moduleID = $get['moduleid'];
		
		
		// TEST SI LE MODULEID EXISTE DANS LA BASE -------------------------------------------------
		if( ! $this::_moduleIdIsValid($moduleID)){
			return json_encode( array('error', "Module ".$moduleID." not declared.") );
		}
		
		// RÉCUPÉRATION DE LA LISTE DES TABLES UTILISÉE PAR CE MODULE ------------------------------
		/*
			SELECT DISTINCT g.tablename
			FROM module as m
			INNER JOIN rel_modulecapteur as mc ON mc.idModule = m.identifiantReseau
			INNER JOIN capteur as c ON c.id = mc.idCapteur
			INNER JOIN rel_capteurgrandeur as cg ON cg.idCapteur = c.id
			INNER JOIN grandeur as g ON g.id = cg.idGrandeur
			WHERE m.identifiantReseau = "AA01"
		 */
		$l_OBJ_Query = new Query();
		$l_TAB_TableNames = $l_OBJ_Query->select("g.tablename")
							->distinct()
							->from("module as m")
							->innerJoin("rel_modulecapteur as mc", "mc.idModule = m.identifiantReseau")
							->innerJoin("capteur as c", "c.id = mc.idCapteur")
							->innerJoin("rel_capteurgrandeur as cg", "cg.idCapteur = c.id")
							->innerJoin("grandeur as g", "g.id = cg.idGrandeur")
							->where('m.identifiantReseau = :identifiantModule', ['identifiantModule' => $moduleID])
							->all();


		// RECUPERATION DES DONNÉES DU MODULES DANS LES TABLES DE MESURES --------------------------
		$l_TAB_Retour	= array();
		foreach( $l_TAB_TableNames as $l_TAB_Temp){
			foreach( $l_TAB_Temp as $l_STR_TableName)
				$l_OBJ_Query = new Query();
				
				$l_TAB_Retour[$l_STR_TableName] = $l_OBJ_Query->select(" timestamp, valeur, posX, posY, posY")
							->from( $l_STR_TableName )
							->where('identifiantModule = :identifiantModule', ['identifiantModule' => $moduleID])
							->limit(100)
							->all();
			
		}
		// RETOUR ----------------------------------------------------------------------------------
		// Le format de l'affichage du message sera en JSON
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$l_TAB_Retour['error']	= "";
		$l_TAB_Retour['success']	= "ok";
		
		return json_encode($l_TAB_Retour);
	}
	
	
	
	
	
	//==============================================================================================
	/**
	 * Permet d'ajouter une mesure dans la base.
	 * La trame doit être sous la forme : AA01/*2018-203703100
	 * 
	 */
	public function actionAdd(){
		// RECUPERATION DU PARAMETRE PASSÉ EN GET --------------------------------------------------
		// @see https://www.yiiframework.com/doc/guide/2.0/fr/runtime-requests
		$request = Yii::$app->request;
		
		$get	= $request->get();
		$moduleID = $get['moduleid'];
		$mesures = $get['mesures'];

		
		// TEST SI LE MODULEID EXISTE DANS LA BASE -------------------------------------------------
		if( ! $this::_moduleIdIsValid($moduleID)){
			$l_TAB_Retour['error']	= "Module ".$moduleID." not declared.";
			return json_encode( $l_TAB_Retour );
		}
		
		
		
		// CONSTRUCTION DE LA REQUETE POUR RÉCUPERER LE NOM DES TABLES OU STOCKER LES DATA A PARTIR DE L'ID DU MODULE 
		/*	Select m.nom, m.identifiantReseau, m.description, m.positionCapteur, c.nom, g.nature, g.tablename, p.x, p.y, p.z
			FROM module as m
			INNER JOIN rel_modulecapteur as rmc ON m.identifiantReseau = rmc.idModule
			INNER JOIN capteur as c ON rmc.idCapteur = c.id
			INNER JOIN rel_capteurgrandeur as rcg ON rcg.idCapteur = c.id
			INNER JOIN grandeur as g ON g.id = rcg.idGrandeur
			INNER JOIN rel_positionCapteur as rpc ON rpc.idCapteur = c.id
			INNER JOIN position as p ON p.id = rpc.idPosition
			WHERE m.identifiantReseau = "AA01"
			
			@see https://www.yiiframework.com/doc/guide/2.0/fr/db-query-builder
		 */
		$l_OBJ_Query= new Query();
		$l_TAB_Results = $l_OBJ_Query->select('m.nom, m.identifiantReseau, m.description, c.nom, g.nature, g.tablename, g.formatCapteur, p.x, p.y, p.z')
					->from('module as m')
					->innerJoin('rel_modulecapteur as rmc', 'm.identifiantReseau = rmc.idModule')
					->innerJoin('capteur as c', 'rmc.idCapteur = c.id')
					->innerJoin('rel_capteurgrandeur as rcg', 'rcg.idCapteur = c.id')
					->innerJoin('grandeur as g', 'g.id = rcg.idGrandeur')
					->innerJoin('rel_positionCapteur as rpc', 'rpc.idCapteur = c.id')
					->innerJoin('position as p', 'p.id = rpc.idPosition')
					->where('m.identifiantReseau = :identifiantReseau', ['identifiantReseau' => $moduleID])
					->all();
					
		
		$l_TAB_Retour['module']['nom'] 				= $module->nom;
		$l_TAB_Retour['module']['description'] 		= $module->description;
		$l_TAB_Retour['module']['positionCapteurs'] = $module->positionCapteur;
		$l_TAB_CapteursID	= explode(";", $module->idCapteur);

		
		
		// CALCUL DU NOMBRE DE CARACTÈRES ATTENDU DANS LA TRAME ------------------------------------
		$l_INT_LongeurAttendu = 0;
		foreach( $l_TAB_Results as $l_INT_Key => $l_TAB_Format){
			list($l_STR_Avant, $l_INT_Apres) = explode(",", $l_TAB_Format['formatCapteur']);
			
			// On ajoute le nombre de caracteres apres la virgule
			$l_INT_LongeurAttendu += $l_INT_Apres;
			
			// Si ce qui est avant la virgule doit contenir un signe, on ajoute 1 à la longueur attendue
			if( strpos($l_STR_Avant, "-") !== false ){
				$l_INT_LongeurAttendu++;
				$l_BOO_Signe = true;
				
			} else {
				$l_BOO_Signe = false;
			}
			
			// On ajoute le nombre de caractère avant la virgule
			$l_INT_Avant	= str_replace("-", "", $l_STR_Avant);
			$l_INT_LongeurAttendu += $l_INT_Avant;
			
			// On sauvegarde le formattage attendu
			$l_TAB_Results[$l_INT_Key]['signe']	= $l_BOO_Signe;
			$l_TAB_Results[$l_INT_Key]['avant']	= $l_INT_Avant;
			$l_TAB_Results[$l_INT_Key]['apres']	= $l_INT_Apres;
		}
		$l_TAB_Retour['Nb caractere attendu']	= $l_INT_LongeurAttendu;
		

		
		// TEST SI LA TRAME FAIT LE BON NOMBRE DE CARACTÈRES ---------------------------------------
		if( strlen( $mesures ) <> $l_INT_LongeurAttendu) {
			$l_TAB_Retour['error']	= "Longeur de trame (partie mesure) incorrecte. ".$l_INT_LongeurAttendu." caract. attendu.";

			// On fait une trace dans la base
			Yii::error("Trame <".$mesures."> du module <".$moduleID."> incorrecte (mauvaise longeur)", "tocio");
			
			
			
			// RENVOIE LE RÉSULTAT EN JSON
			return json_encode($l_TAB_Retour);
		}
		
		
		
		// ENREGISTREMENT DE LA TRAME DANS LES TABLES DE MESURES -----------------------------------
		// 218203-70310
		// On découpe la partie des mesures de la trame selon le formattage récupéré
		$l_TAB_ChaineMesure = str_split($mesures, 1);	// Convertion de la chaine en array
		foreach ( $l_TAB_Results as $l_INT_IndiceMesure => $l_TAB_Capteur){
			// Si on attend un signe
			$l_STR_Signe = "";
			if($l_TAB_Capteur['signe']) {
				$l_STR_Signe = array_shift($l_TAB_ChaineMesure);
			}
			

			
			// avant la virgule
			$l_STR_Avant = "";
			for( $i=0 ; $i < $l_TAB_Capteur['avant']; $i++ ) {
				$l_STR_Avant .= array_shift($l_TAB_ChaineMesure);
			}
			
			
			
			// apres la virgule
			$l_STR_Apres = "";
			for( $i=0 ; $i < $l_TAB_Capteur['apres']; $i++ ) {
				$l_STR_Apres .= array_shift($l_TAB_ChaineMesure);
			}
			
			//  On concatene le tout
			$l_INT_Mesure = floatval($l_STR_Signe . $l_STR_Avant . "." . $l_STR_Apres);
			
			
			
			// Construction de la requète d'insertion
			Yii::$app->db->createCommand()->insert($l_TAB_Capteur['tablename'], [
											'timestamp' => date("Y/m/d H:i:s", time()),
											'valeur' => $l_INT_Mesure,
											'posX' => $l_TAB_Capteur['x'],
											'posY' => $l_TAB_Capteur['y'],
											'posZ' => $l_TAB_Capteur['z'],
											'identifiantModule' => $moduleID,
											])
											->execute();
		}

		
		
		// RETOUR ----------------------------------------------------------------------------------
		// Le format de l'affichage du message sera en JSON
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$l_TAB_Retour['error']	= "";
		$l_TAB_Retour['success']	= "ok";
		
		return json_encode($l_TAB_Retour);
	}
	
	
	
	//==============================================================================================
	/**
	 * Test si le moduleID passé en paramètre existe en base.
	 *
	 * @param string $moduleID = Identifiant unique d'un module
	 * @return true si le résultat est valide, tableau json sinon.
	 */
	private function _moduleIdIsValid($moduleID){
		// TEST SI LE MODULEID EXISTE DANS LA BASE -------------------------------------------------
		$module = Module::findOne($moduleID);
		if( is_null($module)) {
			
			// TRACE DANS LA BASE QUE LE MODULE N'EXISTE PAS
			Yii::error("Le module <".$moduleID."> N'existe pas en base", "tocio");
			
			
			// RENVOIE LE RÉSULTAT EN JSON
			return false;
		} else {
			return true;
		}
	}
	
}
?>