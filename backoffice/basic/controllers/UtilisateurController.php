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
			
			// on compar le mot de passe en BDD et celui venant du formulaire
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
	 * Verify user's token.
	 * @param unknown $token
	 */
	public function actionVerToken( $token ) {
		$model = $this->getToken( $token );
		if( isset( $_POST['Ganti'] ) ) {
			if( $model->token == $_POST['Ganti']['tokenhid'] ) {
				$model->password = md5( $_POST['Ganti']['password'] );
				$model->token = "null";
				$model->save();
				Yii::app()->user->setFlash( 'ganti', '<b>Password has been successfully changed! please login</b>' );
				$this->redirect( '?r=site/login' );
				$this->refresh();
			}
		}
		$this->render( 'pwdverif', array(
				'model' => $model ) );
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 *
	 *@todo : Lire l'email à partir du fichier de config/web.php
	 */
	public function actionPwdforgot() {

		// Read Post Params
		$post 	= Yii::$app->request->post();
		if( isset( $post['email'] )) {
			
			// get Utilisateur from email passed in POST param
			$model	= Utilisateur::model()->findByAttributes( array(
					'email' => $post['email'] ) );
					
					
			// Creat a token with random number and date
			$model->token = md5( rand( 0, 99999 ).date( "H:i:s" ) );
			
			$emailSenderName 	= "Administration TOCIO";
			$emailsSenderEmail 	= "no_reply_tocio@univ-brest.fr";
			$emailSubject 		= "Reset Password";
			$emailContent 		= "you have successfully reset your password<br/>
                    <a href='http://yourdomain.com/index.php?r=site/vertoken/view&token=".$model->token."'>Click Here to Reset Password</a>";
			
			if( $model->validate() ) {
				$name = '=?UTF-8?B?'.base64_encode( $emailSenderName ).'?=';
				$subject = '=?UTF-8?B?'.base64_encode( $emailSubject ).'?=';
				$headers = "From: $name <{$emailsSenderEmail}>\r\n"."Reply-To: {$emailsSenderEmail}\r\n"."MIME-Version: 1.0\r\n"."Content-type: text/html; charset=UTF-8";
				
				// Save the model
				$model->save();
				
				// ?
				Yii::app()->user->setFlash( 'forgot', 'link to reset your password has been sent to your email' );
				
				// Send email
				mail( $getEmail, $subject, $emailContent, $headers );
				$this->refresh();
			}
		}
		$this->render( 'pwdforgot' );
	}
}
