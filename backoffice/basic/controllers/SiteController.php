<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\UploadImageForm;
use yii\web\UploadedFile;
use app\models\Grandeur;
use app\models\GrandeurExportForm;
use app\models\Module;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use app\models\LoginForm;

class SiteController extends Controller {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => [
								'logout' ],
						'rules' => [[
										'actions' => ['logout' ],
										'allow' => true,
										'roles' => [
												'@' ] ] ] ],
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'logout' => [
										'post' ] ] ] ];
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function actions() {
		return [
				'error' => [
						'class' => 'yii\web\ErrorAction' ],
				'captcha' => [
						'class' => 'yii\captcha\CaptchaAction',
						'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null ] ];
	}

	/**
	 * Displays homepage.
	 *
	 * @return string
	 */
	public function actionIndex() {
		return $this->render( 'index' );
	}

	// _____________________________________________________________________________________________
	/**
	 * Login action.
	 *
	 * @return Response|string
	 * @version 18 mai 2020 : APE - Redirection sur la page d'accueil.
	 */
	public function actionLogin() {
		if( ! Yii::$app->user->isGuest ) {
			return $this->goHome();
		}

		// IF LOGIN IS OK
		$model = new LoginForm();
		if( $model->load( Yii::$app->request->post() ) && $model->login() ) {

			// Redirect after login
			return $this->goHome();
		}

		$model->password = '';
		return $this->render( 'login', ['model' => $model ] );
	}

	// _____________________________________________________________________________________________
	/**
	 * Logout action.
	 *
	 * @return Response
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return Response|string
	 */
	public function actionContact() {
		$model = new ContactForm();
		if( $model->load( Yii::$app->request->post() ) && $model->contact( Yii::$app->params['adminEmail'] ) ) {
			Yii::$app->session->setFlash( 'contactFormSubmitted' );

			return $this->refresh();
		}
		return $this->render( 'contact', ['model' => $model ] );
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout() {
		return $this->render( 'about' );
	}

	// _____________________________________________________________________________________________
	/**
	 * Redirect to the Home page.
	 *
	 * {@inheritdoc}
	 * @see \yii\web\Controller::goHome()
	 */
	public function goHome() {
		return Yii::$app->getResponse()->redirect( ['/' ] );
	}
	
	// _____________________________________________________________________________________________
	/**
	 * Permet d'uploader un fichier au format CSV pour le parser et en extraire les Mesure
	 * de Capteur.
	 * 
	 * @return void|string
	 * @see https://www.tutorialspoint.com/yii/yii_files_upload.htm
	 */
	public function actionUpload() {
		$model = new UploadImageForm();
		
		if (Yii::$app->request->isPost) {
			
			// On fait l'upload du fichier CSV
			// Et on rattache le fichier au model UploadImageForm
			$model->CSVFile = UploadedFile::getInstance($model, 'CSVFile');
			
			
			// file is uploaded successfully
			if ($model->upload()) {
				
				// Traitement du fichier
				if( ! $this->_insertDataFromFile($model)) {
					
					// Redirectiopn sur la page d'affichage des données
					return $this->redirect(['/grandeur/index']);
				}
			}
		}
		return $this->render('upload', ['model' => $model]);
	}

	
	public function actionExportDownload(){
		
	}
	
	// _____________________________________________________________________________________________
	/**
	 * Display page to export Grandeurs.
	 * Si on a fait une saisie, on export un fichier CSV dans le flux HTML avec cette saisie.
	 * 
	 * @see https://awesomeopensource.com/project/yii2tech/csv-grid?categoryPage=29
	 *
	 * @return void|string
	 */
	public function actionExport() {
		$model 	= new GrandeurExportForm();
		$tableauCroiseDynamique = [];
		$descriptionDesColonnes	= [];

		// Si on a des parametres qui sont passés en POST dans un formulaire
		if( Yii::$app->request->isPost ) {
			
			// enregistrement des saisie dans le modèle
			$model->load( Yii::$app->request->post());
			
			// si les saisie sont correcte 
			if( $model->validate()){

				// Récupération des data correspondant à la saisie .......................
				$data = $this->_getGrandeurGroupBy( $model );
				
				
				
				
				// Construction du povot pour le tableau .................................
				// Extraction des dates
				$dates = [];
				foreach( $data as $ligne){
					$dates[$ligne['date']]	= $ligne['date'];
				}
				
				// Extraction des identifiant réseaux
				$identifiantModules = [];
				foreach( $data as $ligne){
					$identifiantModules[$ligne['identifiantModule']]	= $ligne['identifiantModule'];
				}
				
				// Transformation des dates en colonnes
				// Pour chaque date, on va chercher les valeur de 
				foreach( $identifiantModules as $identifiantModule){
					$ligneCroisee = [];
					
					// l'Identifiant réseau
					$ligneCroisee['identifiantModule'] = $identifiantModule;
					foreach( $dates as $date){
						$ligneCroisee[$date] = $this->_getCumulForDate( $date, $identifiantModule, $data );
					}
					
					// On ajoute la ligne contrsuite
					$tableauCroiseDynamique[] = $ligneCroisee;
			
				}
				
				// Construction du formattage des colonnes
				$descriptionDesColonnes = ['attribute' => "identifiantModule"];
				foreach ( $dates as $date){
					$descriptionDesColonnes[] = ['attribute' => $date];
				}
				
				
				// Construction de l'export ..............................................
				// @see https://awesomeopensource.com/project/yii2tech/csv-grid?categoryPage=29
				$exporter = new CsvGrid([
						'dataProvider' => new ArrayDataProvider([
								'allModels' => $tableauCroiseDynamique
						]),
						'columns' => $descriptionDesColonnes,
						'csvFileConfig' => [
								'cellDelimiter' => ",",
								'enclosure' => '"'
								],
						'maxEntriesPerFile' => 10000,
				]);
			}
		}
		
	
		
		
		// Construction des listes déroulantes pour le formulaire de saisie
		$TableGrandeurs = Grandeur::find()->all();
		$listMesures	= ArrayHelper::map( $TableGrandeurs, 		'tablename', 'nature' );
		$listModules	= ArrayHelper::map( Module::find()->all(), 	'identifiantReseau', 'nom' );
		
		
		// Récupération des dates min et max des Grandeurs enregistrées
		$tableMesure	= ArrayHelper::map( $TableGrandeurs, 'id', 'tablename' );
		$minMax 		= [date("2021-04-01", time()),date("1970-01-01")];

		// On va analyser chacune des tables de Grandeur pour trouver les dates min et max
		foreach( $tableMesure as $table){
			$temp = $this->_getDateMinMaxFromTable($table);
			
			if( $temp[0] < $minMax[0] ) $minMax[0] = $temp[0];
			if( $temp[1] > $minMax[1] ) $minMax[1] = $temp[1];
		}
		
		
		
		// Envoie des données dans la vue		
		return $this->render( 'export', [
				'listModules' 	=> $listModules,
				'listMesures' 	=> $listMesures,
				'model'			=> $model,
				'dataProvider'	=> $tableauCroiseDynamique,
				'columns'		=> $descriptionDesColonnes,
				'dateMin'		=> $minMax[0],
				'dateMax'		=> $minMax[1],
			]);
	}
	
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Export Grandeurs.
	 *
	 * @see https://awesomeopensource.com/project/yii2tech/csv-grid?categoryPage=29
	 *
	 * @return void|string
	 */
	public function actionDownloadcsv() {
		$model 	= new GrandeurExportForm();
		$tableauCroiseDynamique = [];
		$descriptionDesColonnes	= [];
		
		// Si on a des parametres qui sont passés en POST dans un formulaire
		if( Yii::$app->request->isPost ) {
			
			// enregistrement des saisie dans le modèle
			$model->load( Yii::$app->request->post());
			
			// si les saisie sont correcte
			if( $model->validate()){
				
				// Récupération des data correspondant à la saisie .......................
				$data = $this->_getGrandeurGroupBy( $model );
				
				
				
				
				// Construction du povot pour le tableau .................................
				// Extraction des dates
				$dates = [];
				foreach( $data as $ligne){
					$dates[$ligne['date']]	= $ligne['date'];
				}
				
				// Extraction des identifiant réseaux
				$identifiantModules = [];
				foreach( $data as $ligne){
					$identifiantModules[$ligne['identifiantModule']]	= $ligne['identifiantModule'];
				}
				
				// Transformation des dates en colonnes
				// Pour chaque date, on va chercher les valeur de
				foreach( $identifiantModules as $identifiantModule){
					$ligneCroisee = [];
					
					// l'Identifiant réseau
					$ligneCroisee['identifiantModule'] = $identifiantModule;
					foreach( $dates as $date){
						$ligneCroisee[$date] = $this->_getCumulForDate( $date, $identifiantModule, $data );
					}
					
					// On ajoute la ligne contrsuite
					$tableauCroiseDynamique[] = $ligneCroisee;
					
				}
				
				// Construction du formattage des colonnes
				$descriptionDesColonnes = ['attribute' => "identifiantModule"];
				foreach ( $dates as $date){
					$descriptionDesColonnes[] = ['attribute' => $date];
				}
				
				
				// Construction de l'export ..............................................
				// @see https://awesomeopensource.com/project/yii2tech/csv-grid?categoryPage=29
				$exporter = new CsvGrid([
						'dataProvider' => new ArrayDataProvider([
								'allModels' => $tableauCroiseDynamique
						]),
						'columns' => $descriptionDesColonnes,
						'csvFileConfig' => [
								'cellDelimiter' => ",",
								'enclosure' => '"'
						],
						'maxEntriesPerFile' => 10000,
				]);
				
				
				// Renvoie du fichier CSV généré .....................................
				$exporter->export()->send('exportMesures.csv');
			}
		}
	}
	
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Renvoie la date min et max des mesures faites dans une table.
	 * 
	 * @param string $p_STR_TableName le nom de la table des mesure à analyser.
	 * @return array contenant [minDate, maxDate]
	 */
	private function _getDateMinMaxFromTable($p_STR_TableName){
		$l_STR_Min = date("2021-03-01");
		$l_STR_Max = date("Y-m-d", time());
		
		// Construction de la requète MySQL pour trouver la date la plus petite
		$l_STR_Requete = "SELECT MIN(timestamp) FROM ".$p_STR_TableName;
		$l_STR_Min = Yii::$app->db->createCommand($l_STR_Requete)
								->queryScalar();
								
		// Construction de la requète MySQL pour trouver la date la plus grande
		$l_STR_Requete = "SELECT MAX(timestamp) FROM ".$p_STR_TableName;
		$l_STR_Max = Yii::$app->db->createCommand($l_STR_Requete)
								->queryScalar();
		
		return [$l_STR_Min, $l_STR_Max];
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Cherche la valeur de cumul pour un identifiant réseau et une date.
	 * 
	 * @param unknown $p_STR_date la date à chercher
	 * @param unknown $p_STR_identifiantModule	l'identifiant réseau pour lequel on veut la valeur
	 * @param unknown $p_TAB_data	La structure de données contenant les valeurs.
	 * 
	 * @return string La valeur ou vide si aucune valeur.
	 */
	private function _getCumulForDate($p_STR_date, $p_STR_identifiantModule, $p_TAB_data){
		foreach( $p_TAB_data as $data){

			// On trouve la date pour l'identifiant réseau
			if( $data['identifiantModule'] == $p_STR_identifiantModule && $data['date'] == $p_STR_date){
				
				// Il se peut qu'il n'y ai pas de cumul, mais simplement la valeur
				if( isset($data['cumul'])) {
					return $data['cumul'];
				} else {
					return $data['valeur'];
				}
				
			}
		}
		
		// On a pas trouve de date pour cet identifiant
		return "";
	}
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Fait une requète de cumul du style :
	 * SELECT    identifiantModule, DATE(timestamp) as DATE, SUM(valeur) Cumul
		FROM      tm_temperaturec
		GROUP BY  DATE(timestamp), identifiantModule
		
	 * @param array contenant le résultat rows by rows de la requète.
	 */
	private function _getGrandeurGroupBy($model){
		
		// Construction du cumul .........................................................
		switch ($model->cumulBy){
			case "day":
				$groupBy	= "DATE(timestamp)";
				break;
			case "month":
				$groupBy	= "YEAR(timestamp), MONTH(timestamp)";
				break;
			case "year":
				$groupBy	= "YEAR(timestamp)";
				break;
		}
		
		// Construction du filtre sur le Module ..........................................
		$where[] =  "true"; // To protect from empty filter
		if($model->moduleName != "all" )$where[] =  " identifiantModule = :modulename ";
		
		
		
		// Construction du filtrage sur les dates ........................................
		// On a une date de départ
		if( $model->dateStart != "")	$where[] = " timestamp >= DATE('".$model->dateStart."')"; 

		// On a une date de fin
		if( $model->dateEnd != "") 		$where[] =  " timestamp <= DATE_ADD(DATE('".$model->dateEnd."'), INTERVAL 1 DAY)"; 
		
		
		
		// Construction de la requete de cumul ...........................................
		// SI on a aucun cumul voulu
		// ... on fait une requète sans cumul ni group by
		if( $model->cumulBy == "none") {
			$l_STR_requete	= "SELECT identifiantModule, timestamp as date, valeur
							FROM ".$model->tableName.
							" WHERE ".implode($where, " AND ")." ;";
		} else {
			$l_STR_requete = "SELECT identifiantModule, ".$groupBy." as date, SUM(valeur) cumul
							FROM ".$model->tableName.
							" WHERE ".implode($where, " AND ").
							" GROUP BY ".$groupBy." , identifiantModule;";
		}
		
		// pour debug de la requete
		$debug = Yii::$app->db->createCommand($l_STR_requete)
			->bindParam(':modulename', $model->moduleName)
			->getSql();
		
		// Renvoie le résultat de la requète.
		return Yii::$app->db->createCommand($l_STR_requete)
					->bindParam(':modulename', $model->moduleName)
					->queryAll();
	}
	
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Insertion des données ligne par ligne du fichier CSV envoyé.
	 * Si tout se passe bien on renvoie true, False sinon.
	 * 
	 * On lit chacune des lignes pour reconstruire la payload qui sera envoyée dans l'API.
	 * 
	 * @return boolean 
	 */
	private function _insertDataFromFile($model) {
		$error 		= false;
		// Autodetection des fins de lignes
		// Ceci est sencé améliorer la compatibilité de lecture avec les fichiers de sources Unix ou MAC. 
		ini_set('auto_detect_line_endings',TRUE);
		
		//  Ouverture du fichier CSV en lecture
		if (($handle = fopen($model->fileName, "r")) !== false) {
			$numLigne 	= 1;
			
			// lecture ligne par ligne du fichier
			while (($l_TAB_champs = fgetcsv($handle, 0, ";", "\"")) !== false) {
				
// 				if( !is_null($ligne)){
				if( is_array($l_TAB_champs)){
					// Séparation des champs de la ligne
					$idModule 	= $l_TAB_champs[0];
					$timeStamp 	= $l_TAB_champs[1];
					$payload 	= "";
					
					// Concaténation de la Payload Champ par champ
					// On fait une concaténation avec un caractère vide pour avoir une chaine de 
					// caractère et non pas des integer, car sinon on peut perdre le 0 devant le 
					// nombre, ce qui va provoquer une erreur sur la taille de la chaine attendu.
					for ($c=2; $c < count($l_TAB_champs); $c++) {
						$payload .= $l_TAB_champs[$c];
					}
					
					// Insertion des mesures de la ligne dans la base (via le controleur des Mesures)
					$l_TAB_Retour = MesureController::enregistreMesure($idModule, $payload, $timeStamp);
					
					// Si on a une erreur à l'insertion.
					if( isset($l_TAB_Retour['error']) and $l_TAB_Retour['error'] != ""){
						$error = true;
						$model->ErrorMessages[] = "Problème à l'enregistrement de la ligne $numLigne : <b><br/>". implode(",", $l_TAB_champs)."</b><br/><span class='error'>". $l_TAB_Retour['error']."</span>";
					}
				
				// La ligne lue est vide
				} else {
					$error = true;
					$model->ErrorMessages[] = "Impossible de trouver les champs de la ligne $numLigne, est-ce vraiment un fichier CSV correctement formaté ?";
				}
				$numLigne++;
			}
			fclose($handle);
		}
		
		// Suppression du fichier CSV
		if( is_file($model->fileName)) {
			unlink($model->fileName);
		}
		
		
		return $error;
	}
	
}
