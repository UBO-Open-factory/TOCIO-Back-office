<?php

namespace app\controllers;

use Yii;
use app\models\Method;
use app\models\Cartes;
use app\models\MethodSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Relcartesmethod;

/**
 * MethodController implements the CRUD actions for Method model.
 */
class MethodController extends Controller {
	public $idtoto;
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
	 * Lists all Method models.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new MethodSearch();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		return $this->render( 'index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider ] );
	}

	/**
	 * Displays a single Method model.
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
	 * Creates a new Method model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 *
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Method();

		if( $model->load( Yii::$app->request->post() ) && $model->save() ) {
			return $this->redirect( [
					'index',
					'id' => $model->id ] );
		}

		$method_pre['include'] = "//add include methode for librairies";
		$method_pre['statement'] = "//add declaration methode for your sensor";
		$method_pre['setup'] = "//add setup methode to begin your sensor";
		$method_pre['read'] = "//add all reading methode for your sensor , foreach length";

		$method_pre['list'] = array_combine( Cartes::find()->select( ['id' ] )->indexBy( 'nom' )->column(), Cartes::find()->select( ['nom' ] )->indexBy( 'nom' )->column() );

		return $this->render( 'create', [
				'model' => $model,
				'method_pre' => $method_pre ] );
	}

	/**
	 * Updates an existing Method model.
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

		$method_pre['id_capteur'] = $model['id_capteur'];
		$method_pre['include'] = $model['method_include'];
		$method_pre['statement'] = $model['method_statement'];
		$method_pre['setup'] = $model['method_setup'];
		$method_pre['read'] = $model['method_read'];
		$method_pre['id'] = $model['method_include'];

		$list = str_replace( ' ', '', Cartes::find()->select( [
				'nom' ] )->indexBy( 'nom' )->column() );
		$method_pre['list'] = array_combine( $list, $list );

		return $this->render( 'update', [
				'model' => $model,
				'method_pre' => $method_pre ] );
	}

	/**
	 * Deletes an existing Method model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete( $id ) {
		foreach( relcartesmethod::find()->where( [
				"id_method" => $id ] )->all() as $id_method ) {
			relcartesmethod::find()->where( [
					"id_method" => $id ] )->one()->delete();
		}

		$this->findModel( $id )->delete();

		return $this->redirect( [
				'index' ] );
	}

	/**
	 * Finds the Method model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return Method the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel( $id ) {
		if( ($model = Method::findOne( $id )) !== null ) {
			return $model;
		}

		throw new NotFoundHttpException( 'The requested page does not exist.' );
	}
	
	
	/**
	 * update a method model with ajax
	 * @param int $_POST["id"]
	 * @return string succes
	 * @version 28 mai 2021
	 */	
	public function actionUpdateajax() {
		$request = Yii::$app->request;
		// UPDATE FAIT À L'AIDE D'UNE REQUÈTE AJAX -------------------------------------------------
		if( Yii::$app->request->isAjax && $request->post() ) {
			$post = $request->post();
			$model = $this->findModel( $post['id'] );

			// Récupération de l'attribut à mettre à jour
			$attributeName = $post['attribute'];

			// Récupération de la valeur de l'attribut
			$value = $post['value'];

			// Mise à jour de l'attribut
			$model->setAttribute( $attributeName, $value );

			// Le retour sera au format JSON
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

			// Sauve le model
			if( $model->save() ) {
				return [
						"success" => "ok" ];
			} else {
				return [
						"error" => "ok" ];
			}
		}
	}
}
