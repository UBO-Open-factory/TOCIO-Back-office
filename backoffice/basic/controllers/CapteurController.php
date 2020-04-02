<?php

namespace app\controllers;

use Yii;
use app\models\Capteur;
use app\models\CapteurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CapteurController implements the CRUD actions for Capteur model.
 */
class CapteurController extends Controller
{
	
	
	// ---------------------------------------------------------------------------------------------
	/**
	 * Renvoie la liste des Capteur au format JSON.
	 *
	 * @return string JSON
	 * @see https://www.yiiframework.com/doc/guide/2.0/fr/output-data-providers
	 */
	public function actionGetcapteurs(){
		// Le format de l'affichage du modele serra en JSON
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		// Renvoie tout ce qui est dans la table "Grandeur"
		return Capteur::find()->all();
	}
	
	
	// ---------------------------------------------------------------------------------------------
	/**
	 * Renvoie une Grandeur dont l'ID est passé en paramètre au format JSON.
	 *
	 * @param integer $id : l'ID de la grandeur dont on veut la définition.
	 * @return string  JSON
	 */
	public function actionGetcapteur($id){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		return Capteur::find()
		->where(['id' => $id])
		->one();
	}
	
	
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Capteur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CapteurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Capteur model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Capteur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Capteur();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Capteur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Capteur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Capteur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Capteur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Capteur::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
