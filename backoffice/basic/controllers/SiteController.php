<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadImageForm;
use yii\web\UploadedFile;
use app\models\Grandeur;

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
// 			while (($ligne = fgets($handle)) !== false) {
			while (($l_TAB_champs = fgetcsv($handle, 0, ";", "\"")) !== false) {
				
// 				if( !is_null($ligne)){
				if( is_array($l_TAB_champs)){
					// Séparation des champs de la ligne
// 					$l_TAB_champs	= str_getcsv( $ligne, ";");
// 					$ligne = str_replace('"', "", $ligne);
// 					$l_TAB_champs	= explode( ";", $ligne);

					
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
