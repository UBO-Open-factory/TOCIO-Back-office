<?php

namespace app\controllers;

use Yii;
use app\models\Utilisateur;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UtilisateurSearch;
use yii\filters\AccessControl;
use app\models\AuthAssignment;
use app\models\LoginForm;
use PharIo\Manifest\Url;
use yii\web\UrlManager;
use Elasticsearch\Endpoints\Cat\Aliases;
use phpDocumentor\Reflection\Types\Null_;

/**
 * UtilisateurController implements the CRUD actions for Utilisateur model.
 */
class UtilisateurController extends Controller {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [ 
			'access' => [ 
				'class' => AccessControl::className (),
				'only' => ['create','update','delete'],
				'rules' => [ 
					[ 
						'allow' => true,
						'actions' => ['create','update','delete'],
						'roles' => ['@'] // Authenticated users | (?) for anonymous user
					]
				]
			],
			'verbs' => [ 
				'class' => VerbFilter::className (),
				'actions' => [ 
					'delete' => ['POST']
				]
			]
		];
	}

	/**
	 * Lists all Utilisateur models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new UtilisateurSearch ();
		$dataProvider = $searchModel->search ( Yii::$app->request->queryParams );

		return $this->render ( 'index', [ 
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider
		] );
	}

	/**
	 * Displays a single Utilisateur model.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->render ( 'view', [ 
				'model' => $this->findModel ( $id )
		] );
	}

	//_____________________________________________________________________________________________
	/**
	 * Creates a new Utilisateur model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 * @version 13 mai 2020	: APE	- Assignation d'un groupe de droit.
	 */
	public function actionCreate() {
		$model	= new Utilisateur();
		$groupe	= new AuthAssignment();

		if ($model->load( Yii::$app->request->post() )) {
			// Encodage du mot de passe
			$model->password = Yii::$app->getSecurity()->generatePasswordHash( $model->password );

			// Sauvegarde du model
			$model->save();
			
			
			// Assignation du groupe 
			$groupe->load( Yii::$app->request->post() );
			$groupe->user_id = $model->id;
			$groupe->created_at = time();
			
			// Si le group est valide
			if( $groupe->validate()) {
				// Sauvegarde du groupe
				$groupe->save();
				
				// Redirection 
				return $this->redirect( [ 
						'index',
						'id' => $model->id
				] );
			}
			
		}

		return $this->render( 'create', [ 
				'model' 	=> $model,
				'groupe' 	=> $groupe, 
		] );
	}

	// _____________________________________________________________________________________________
	/**
	 * Updates an existing Utilisateur model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate( $id) {
		$post 	= Yii::$app->request->post();
		$model	= $this->findModel( $id );
		$groupe = AuthAssignment::findOne( ['user_id' => $id] );
		$passwdHasChange = false;

		
		// Est-ce que le mot de passe a changé ?
		if( isset( $post['Utilisateur']['password'] )) {
			
			// on compare le mot de passe en BDD et celui venant du formulaire
			$passwdHasChange = ($model->password <> $post['Utilisateur']['password']);
		}

		if( $model->load( Yii::$app->request->post() ) && $groupe->load( Yii::$app->request->post() ) && $groupe->save() ) {

			// Si le mot de passe à changé
			if( $passwdHasChange ) {
			// Encodage du nouveau mot de passe
				$model->password = Yii::$app->getSecurity()->generatePasswordHash( $model->password );
			}

			// On sauve la modification de l'Utilisateur
			if( $model->save() ) {

				// Redirection sur la liste des utilisateurs
				return $this->redirect( [ 
						'index',
						'id' => $model->id
				] );
			}
		}

		return $this->render( 'update', [ 
				'model' => $model,
				'groupe' => $groupe
		] );
	}

		// _____________________________________________________________________________________________
	/**
	 * Deletes an existing Utilisateur model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 *        
	 */
	public function actionDelete( $id) {
		// Suppression de la relation avec le groupe
		$groupe = AuthAssignment::findOne(['user_id'=> $id] );
		$groupe->delete();
		
		// Suppression du model courant
		$this->findModel( $id )->delete();
		
		// Redirection sur la liste
		return $this->redirect( [ 
				'index'
		] );
	}

	/**
	 * Finds the Utilisateur model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return Utilisateur the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id) {
		if( ($model = Utilisateur::findOne( $id )) !== null ) {
			return $model;
		}

		throw new NotFoundHttpException( 'The requested page does not exist.' );
	}
	
	
	
	// _____________________________________________________________________________________________
	/**
	 * Give a user from it's token.
	 *
	 * @param unknown user's $token
	 * @throws HttpException
	 * @return User Model
	 */
	public function getToken( $token ) {
		$model = Utilisateur::model()->findByAttributes( array( 'token' => $token ) );
		if( $model === null )
			throw new NotFoundHttpException( 'The requested page does not exist.' );
			return $model;
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Verify user's token to allow password reset.
	 * 
	 * @param string $token
	 */
	public function actionPwdverif( $token ) {
		// Read token in URL 
		$get = Yii::$app->request->get();
		
		if( isset( $get['token'])) {
			$token = $get['token'];
			
			// Get the Utilisateur model from token
			$model = Utilisateur::findOne(['accessToken' => $get['token']]);

			// We got a Utilisateur with this token
			if( !is_null($model)) {
				
				// Reset the password (to avoid it to be displayed in the form)
				$model->password = "";
				
				
				// Display the page
				return $this->render( 'pwdverif', array(
						'model' => $model ) );
			}
		}
		
		// Redirection to the home page
		return $this->goHome();
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * This create a tocken to allow password reset.
	 * The tocken is send in a link in an email to the user. When he click on the lihnk, the sended tocken is
	 * compare to the database tocken for the input email & passwd. If it match, the password will be saved.
	 * 
	 *@todo : Lire l'email du sender à partir du fichier de config/web.php et envoyer l'email avec l'URL
	 * au lieux de faire la redirection sur l'URL.
	 */
	public function actionPwdforgot() {

		// Read Post Params
		$post 	= Yii::$app->request->post();
		if( isset( $post['Utilisateur']['email'] )) {
			
			// Input Email (from the form)
			$userEmail = $post['Utilisateur']['email'];
			
			// get Utilisateur from email passed in POST param
			$model	= Utilisateur::findOne(['email' => $userEmail] );
					
			// If we got a Utilisateur with this email
			if( !is_null($model)) {
						
				// Create a token with random number and date
				$model->accessToken = md5( rand( 0, 99999 ).date( "H:i:s" ) );
	
				// Create URL with the token
				$url = \yii\helpers\Url::toRoute(['utilisateur/pwdverif', "token" => $model->accessToken]);
				
				
				
				
				if( $model->validate() ) {
					
					// Save the model (specially the token)
					$model->save();
					
					// Display short message on screen
					Yii::$app->session->setFlash('forgot', 'A link to reset your password has been sent to your email' );
				
					
				// Initiate the email sender
				$emailSenderName 	= Yii::$app->params['senderName'];
				$emailSubject		= "TOCIO : Reset Your password";
				$emailsSenderEmail	= Yii::$app->params['senderEmail'];

				
				
				
				// Create email content
				$name = '=?UTF-8?B?'.base64_encode( $emailSenderName ).'?=';
				$subject = '=?UTF-8?B?'.base64_encode( $emailSubject ).'?=';
				$headers = "From: $name <{$emailsSenderEmail}>\r\n"."Reply-To: {$emailsSenderEmail}\r\n"."MIME-Version: 1.0\r\n"."Content-type: text/html; charset=UTF-8";
				
				$emailSenderName 	= "Administration TOCIO";
				$emailsSenderEmail 	= "no_reply_tocio@univ-brest.fr";
				$emailSubject 		= "Reset Password";
				$emailContent 		= "You ask to reset your password<br/>
	                    <a href='$url'>Click Here to Reset Password</a>";

				// Send email
				mail( $userEmail, $subject, $emailContent, $headers );
				$this->refresh();
				
				// Redirection to the home page
				return $this->goHome();
				}
			}
		}
		$model = new Utilisateur();
		
		// Send Utilisateur modele to the "pwdforgot" page
		return $this->render( 'pwdforgot', array(
				'model' => $model ));
	}
	
	// _____________________________________________________________________________________________
	/**
	 * Updates an existing Utilisateur model.
	 * If update is successful, the browser will be redirected to the 'login' page.
	 *
	 * @return mixed
	 */
	public function actionPwdupdate() {
		$post 	= Yii::$app->request->post();
		$id = $post['Utilisateur']['id'];
		
		// Récupération de l'Utilisateur
		$model	= $this->findModel( $id );
		
		// Encodage du nouveau mot de passe
		$model->password = Yii::$app->getSecurity()->generatePasswordHash( $post['Utilisateur']['password'] );
		
		// Suppression du token
		$model->accessToken = "";
			
		// On sauve la modification de l'Utilisateur
		$model->save();
			
		// redirect to the login page
		$model = new LoginForm();
		return $this->render( \yii\helpers\Url::toRoute(['site/login']), array(
				'model' => $model ));
	}
}
