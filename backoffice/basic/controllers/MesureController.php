<?php
namespace app\controllers;
use Yii;

use yii\rest\ActiveController;
use app\models\Module;
use yii\db\Query;
use yii\filters\VerbFilter;
use function GuzzleHttp\Psr7\str;
use Elasticsearch\ClientBuilder;



/**
 * Controlleur permettant d'ajouter une mesure dans la base.
 *
 * @file MesureController.php
 * @author Alex
 */
class MesureController extends ActiveController {
	public $modelClass = 'app\models\mesure';
	
	
	
	//==============================================================================================
	/**
	 * Renvoie les 100 dernières mesures d'un modules.
	 *
	 * @return array au format JSON.
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
		
		// RÉCUPÉRATION DE LA LISTE DES TABLES UTILISÉES PAR CE MODULE -----------------------------
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
	 * Permet d'ajouter une trame provenant de Lora dans la base.
	 * Exemple de trame recus:
	 * {
	 "metadata": {
	 "connector": "lora",
	 "source": "urn:lo:nsid:lora:70B3D54999F0552D",
	 "group": {
	 "path": "/",
	 "id": "root"
	 },
	 "network": {
	 "lora": {
	 "signalLevel": 3,
	 "rssi": -113,
	 "gatewayCnt": 2,
	 "esp": -116.01,
	 "sf": 7,
	 "messageType": "UNCONFIRMED_DATA_UP",
	 "port": 2,
	 "snr": 0,
	 "ack": false,
	 "location": {
	 "alt": 0,
	 "accuracy": 10000,
	 "lon": -4.438382,
	 "lat": 48.498604
	 },
	 "fcnt": 0,
	 "devEUI": "70B3D54999F0552D"
	 }
	 }
	 },
	 "streamId": "urn:lo:nsid:lora:70B3D54999F0552D",
	 "created": "2020-04-01T15:21:44.996Z",
	 "extra": {},
	 "location": {
	 "provider": "lora",
	 "alt": 0,
	 "accuracy": 10000,
	 "lon": -4.438382,
	 "lat": 48.498604
	 },
	 "model": "lora_v0",
	 "id": "5e84b188b112b25b2cb6d638",
	 "value": {
	 "payload": "53616c75742021"
	 },
	 "timestamp": "2020-04-01T15:21:29.683Z",
	 "tags": []
	 }
	 */
	public function actionAddlora(){
		//on recupere les données du json envoyé
		$json 	= \Yii::$app->request->getRawBody();
		$params	= json_decode( $json, true );
		
		// Recuperation du streamId envoyé par LORA en quise d'ID unique de module.
		$moduleID 	= $params['metadata']['network']['lora']['devEUI'];
		
		
		// SI L'ID DU MODULE N'EST PAS RÉFÉRENCÉ DANS LA BASE --------------------------------------
		if( !$this->_moduleIdIsValid($moduleID)){
			// On fait une trace dans la base
			Yii::error("Module <".$moduleID."> inconnu dans la base.", "tocio");
			
			// Renvoie un message d'erreur
			return json_encode( ['error'	=> "Module ".$moduleID." not declared."] );
		}
		
		
		// SI LE MODULE EST DESACTIVE --------------------------------------------------------------
		$module = Module::findOne($moduleID);
		if( $module->actif == 0){
			// On fait une trace dans la base
			Yii::error("Module <".$moduleID."> désactivé.", "tocio");
			
			// Renvoie un message d'erreur
			return json_encode( ['error'	=> "Module ".$moduleID." disabled."] );
		}
		
		
		$timestamp	= $params['timestamp'];
		$payloadBrute	= $params['value']['payload'];
		$mesures		= $this->_hex2str($payloadBrute);
		
		
		// ENREGISTRE LA MESURE --------------------------------------------------------------------
		return $this->_storeMesure($moduleID, $mesures);
		
	}
	
	
	//==============================================================================================
	/**
	 * Empèche l'accès à l'action addlora en GET ( le fait que l'on soit en GET ou en POST est géré
	 * dans l'urlManager au niveau des rules dans le fichier config/web.php.
	 *
	 * @return unknown
	 */
	public function actionAddloraget(){
		return json_encode(['erreur' => 'POST uniquement']);
	}
	
	
	
	
	
	
	//==============================================================================================
	/**
	 * Permet d'ajouter une mesure dans la base.
	 * La trame doit être sous la forme : AA01/2018-203703100
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
			// On fait une trace dans la base
			Yii::error("Trame <".$moduleID."> inconnu dans la base.", "tocio");
			
			$l_TAB_Retour['error']	= "Module ".$moduleID." not declared.";
			return json_encode( $l_TAB_Retour );
		}
		
		
		// ENREGISTRE LA MESURE
		return $this->_storeMesure($moduleID, $mesures);
	}
	
	
	
	
	
	//==============================================================================================
	/**
	 * Enregistre en base une mesure envoyée par un capteur.
	 * @param  $moduleID l'ID du module pour lequel enregistrer la mesure.
	 * @param $mesures la mesure brute à enregistrer.
	 * @return vide ou message au format JSON
	 * @version 17 juin 2020	: APE	- Remplacement des points dans la mesure par des virgules.
	 * 	@version 3 juil. 2020	: APE	- Remplacement des virgules dans le formattage de mesure par des points.
	 * 	@version 3 juil. 2020	: APE	- Implémentation de l'ajout dans Elasticsearch
	 * 	@version 17 sept. 2020	: APE	- Suppression de l'utilisation de json_encode pour le retour de la fonction.
	 * 									- Affichage des data inséréés dans ElasticSearch.
	 * 	@version 14 oct. 2020	: APE	- Prise en compte de l'ordre des capteurs (ajout du "orderBy") 
	 * 										lors de la récupéraiton de la liste des capteurs rattachés au module.
	 */
	private function _storeMesure($moduleID, $mesures){
		$l_TAB_Retour = array();
		// CONSTRUCTION DE LA REQUETE POUR RÉCUPERER LE NOM DES TABLES OU STOCKER LES DATA A PARTIR DE L'ID DU MODULE
		/*	Select m.nom, m.identifiantReseau, m.description, c.nom, g.nature, g.tablename, rmc.x, rmc.y, rmc.z
		 FROM module as m
		 INNER JOIN rel_modulecapteur as rmc ON m.identifiantReseau = rmc.idModule
		 INNER JOIN capteur as c ON rmc.idCapteur = c.id
		 INNER JOIN rel_capteurgrandeur as rcg ON rcg.idCapteur = c.id
		 INNER JOIN grandeur as g ON g.id = rcg.idGrandeur
		 INNER JOIN position as p ON p.id = c.idPosition
		 WHERE m.identifiantReseau = "70B3D54999F0552D"
		 
		 @see https://www.yiiframework.com/doc/guide/2.0/fr/db-query-builder
		 */
		$l_OBJ_Query= new Query();
		$l_TAB_Results = $l_OBJ_Query->select('m.nom, m.identifiantReseau, m.description, c.nom as capteurnom, g.nature, g.tablename, g.formatCapteur, rmc.x, rmc.y, rmc.z, rmc.nomcapteur, rmc.ordre')
									->from('module as m')
									->innerJoin('rel_modulecapteur as rmc', 'm.identifiantReseau = rmc.idModule')
									->innerJoin('capteur as c', 'rmc.idCapteur = c.id')
									->innerJoin('rel_capteurgrandeur as rcg', 'rcg.idCapteur = c.id')
									->innerJoin('grandeur as g', 'g.id = rcg.idGrandeur')
									->where('m.identifiantReseau = :identifiantReseau', ['identifiantReseau' => $moduleID])
									->orderBy(['rmc.ordre'=> SORT_ASC])
									->all();

		
									
									
									
									
									
		// CALCUL DU NOMBRE DE CARACTÈRES ATTENDU DANS LA TRAME ------------------------------------
		$l_INT_LongeurAttendu = 0;
		foreach( $l_TAB_Results as $l_INT_Key => $l_TAB_Format){
			// Remplacement des virgules par des points
			$l_TAB_Format['formatCapteur'] = str_replace(",", ".", $l_TAB_Format['formatCapteur']);
			
			// Récupération de ce qui est avant et apres le séparateur (le point)
			list($l_STR_Avant, $l_INT_Apres) = explode(".", $l_TAB_Format['formatCapteur']);
			
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
			$l_TAB_Retour['error']	= "Longeur de trame (partie mesure) incorrecte. ".$l_INT_LongeurAttendu." caract. attendu (".strlen( $mesures )." recus)";
			
			// On fait une trace dans la base
			Yii::error("Trame <".$mesures."> du module <".$moduleID."> incorrecte (mauvaise longeur)", "tocio");
			
			
			
			// RENVOIE LE RÉSULTAT EN JSON
			return json_encode($l_TAB_Retour);
		}
		
		
		// CREEATION DU CLIENT ELASTIC SEARCH ------------------------------------------------------
		// Pour la configuration du client elastic @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/configuration.html
		$l_OBJ_ClientElastic = ClientBuilder::create()->build();
		$elasticRespons		= array();
		
		
		// ENREGISTREMENT DE LA TRAME DANS LES TABLES DE MESURES -----------------------------------
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
			
			
			
			// Construction de la requète d'insertion en BDD
			$params = [
					'timestamp' => date("Y/m/d H:i:s", time()),
					'valeur' => $l_INT_Mesure,
					'posX' => $l_TAB_Capteur['x'],
					'posY' => $l_TAB_Capteur['y'],
					'posZ' => $l_TAB_Capteur['z'],
					'identifiantModule' => $moduleID,
			];
			Yii::$app->db->createCommand()
							->insert($l_TAB_Capteur['tablename'], $params)
							->execute();
			
			// Insertion dans Elastic Search
			// @see https://www.elastic.co/guide/en/elasticsearch/client/php-api/current/indexing_documents.html
			$params['timestamp']	= date(DATE_ATOM, time());
			$params['Module identifiant reseau'] = $l_TAB_Capteur['identifiantReseau'];
			$params['Module description'] 		= $l_TAB_Capteur['description'];
			$params['Module nom'] 				= $l_TAB_Capteur['nom'];
			$params['Capteur nom custom'] 		= $l_TAB_Capteur['nomcapteur'];
			$params['Capteur nom'] 				= $l_TAB_Capteur['capteurnom'];
			$params['Mesure nature'] 			= $l_TAB_Capteur['nature'];
			$elasticData		= ['index' 	=> Yii::getAlias('@elasticsearchindex'),
									'body'		=> $params
									];
			$respons 			= $l_OBJ_ClientElastic->index($elasticData);
			$elasticRespons[] 	= $respons;
		}
		
		
		
		
		// RETOUR ----------------------------------------------------------------------------------
		// Le format de l'affichage du message sera en JSON
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$l_TAB_Retour['error']	= "";
		$l_TAB_Retour['success']	= "ok";
		$l_TAB_Retour['ElasticSearchDataInsertion'] = $elasticData;
		$l_TAB_Retour['ElasticSearchReturn'] = $elasticRespons;
		
		return $l_TAB_Retour;
		
		
	}
	
	
	
	//==============================================================================================
	/**
	 * Test si le moduleID passé en paramètre existe en base.
	 *
	 * @param string $moduleID = Identifiant unique d'un module
	 * @return true si le résultat est valide, tableau json sinon.
	 * 	@version 18 mai 2020	: APE	- L'ID réseau n'est pas sensible à la casse.
	 */
	private function _moduleIdIsValid($moduleID){
		// TEST SI LE MODULEID EXISTE DANS LA BASE -------------------------------------------------
		$module = Module::findOne(strtoupper($moduleID));
		if( is_null($module)) {
			
			// TRACE DANS LA BASE QUE LE MODULE N'EXISTE PAS
			Yii::error("Le module <".$moduleID."> N'existe pas en base", "tocio");
			
			return false;
		} else {
			return true;
		}
	}
	
	
	
	//==============================================================================================
	/**
	 * Convertie une chaine hexadécimale en caractères ASCII.
	 *
	 * @param string $hex
	 * @return string
	 */
	private function _hex2str($hex) {
		$str = '';
		for($i=0;$i<strlen($hex);$i+=2) $str .= chr(hexdec(substr($hex,$i,2)));
		return $str;
	}
}
?>