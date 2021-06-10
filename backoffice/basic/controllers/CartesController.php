<?php

namespace app\controllers;

use Yii;
use app\models\Cartes;
use app\models\Method;
use app\models\CartesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * CartesController implements the CRUD actions for Cartes model.
 */
class CartesController extends Controller {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'delete' => [
										'POST' ] ] ] ];
	}

	/**
	 * Lists all Cartes models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new CartesSearch();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider ] );
	}

	/**
	 * Displays a single Cartes model.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView( $id ) {
		return $this->render( 'view', [
				'model' => $this->findModel( $id ) ] );
	}

	/**
	 * Creates a new Cartes model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Cartes();

		if( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect([Url::previous()]);
		}
		return $this->render( 'create', ['model' => $model ] );
	}

	/**
	 * Updates an existing Cartes model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate( $id ) {
		$model = $this->findModel( $id );

		if( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( [
					'index',
					'id' => $model->id ] );
		}

		return $this->render( 'update', [
				'model' => $model ] );
	}

	/**
	 * Deletes an existing Cartes model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete( $id ) 
	{
		foreach( method::find()->where( ["id_carte" => $id ])->all() as $method ) 
		{
			method::find()->where(["id_carte" => $id ])->one()->delete();
		}

		$this->findModel( $id )->delete();

		return $this->redirect( ['index' ] );
	}

	/**
	 * Finds the Cartes model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return Cartes the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id ) {
		if( ($model = Cartes::findOne( $id )) !== null ) {
			return $model;
		}

		throw new NotFoundHttpException( 'The requested page does not exist.' );
	}

	// _____________________________________________________________________________________________
	/**
	 * Updates an existing cartes model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 * @version 23 avr. 2020: APE - Création.
	 * @version 11 mai 2020 : APE - Add catch block around findModel function
	 */
	public function actionAjaxupdate() {
		$request = Yii::$app->request;
		// Le retour sera au format JSON
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		// AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
		if( Yii::$app->request->isAjax && $request->post() ) {

			$post = $request->post();
			try {
				$model = $this->findModel( $post['id'] );
			} catch( Exception $e ) {
				return [
						"success" => "** Oupsss, impossible de trouver le model ".$model::className()."\n",
						"errors" => json_encode( $e->getMessage() ) ];
			}
			$model->nom = $post['nom'];

			// Sauve le model
			if( $model->save() ) {
				// Renvoie le dernier ID créé
				return [
						"success" => "ok",
						"lastID" => $model->id ];
			}
		}

		// If we are here that mean something goes wrong, so we dump and return error.
		return [
				"success" => "** Oupsss, il y a eu un problème à la mise à jour de la carte ".$model::className()."\n",
				"errors" => json_encode( $model->errors ) ];
	}

	// _____________________________________________________________________________________________
	/**
	 * Creates a new Cartes model with Ajax request
	 *
	 * @return mixed
	 * @version 23 avr. 2020: APE - Création.
	 */
	public function actionAjaxcreate() {
		$request = Yii::$app->request;

		// AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
		if( Yii::$app->request->isAjax && $request->post() ) {

			// CONSTRUCTION DU MODEL
			$post = $request->post();
			$model = new Cartes();

			// Initialiastion des attributs avec les valeurs passées en paramètre
			foreach( $post as $name => $value ) {
				$model->setAttribute( $name, $value );
			}

			// Le retour sera au format JSON
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			// Sauve le model
			if( $model->save() ) {
				// Renvoie le dernier ID créé
				return [
						"success" => "ok",
						"lastID" => $model->id ];
			} else {
				var_dump( $model->errors );
				return [
						"success" => "** Oupsss, il y a eu un problème à la création du model ".$model::className()."\n",
						"errors" => json_encode( $model->errors ) ];
			}
		}
	}
}
