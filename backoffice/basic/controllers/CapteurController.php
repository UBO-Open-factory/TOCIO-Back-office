<?php

namespace app\controllers;

use Yii;
use app\models\Capteur;
use app\models\CapteurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Relcapteurgrandeur;

/**
 * CapteurController implements the CRUD actions for Capteur model.
 */
class CapteurController extends Controller
{
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

    // _____________________________________________________________________________________________
    /**
     * Updates an existing Capteur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * 	@version 23 avr. 2020	: APE	- Création.
     */
    public function actionAjaxupdate() {
    	$request 	= Yii::$app->request;
		$post		= $request->post();
    		
		
		// AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		$model = $this->findModel($post['id']);
    		$model->nom = $post['nom'];
    		
    		// Le retour sera au format JSON
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		// Sauve le model
    		if( $model->save() ){
    			// Renvoie le dernier ID créé
    			return ["success" 	=> "ok",
    					"lastID" 	=> $model->id,
    			];
    		} else {
    			var_dump($model->errors);
    			return ["success" => "** Oupsss, il y a eu un problème à la mise à jour du model ".$model::className()."\n", "errors" => json_encode($model->errors)];
    		}
    	}
    }
    // _____________________________________________________________________________________________
    /**
     * Creates a new Capteur model with Ajax request
     * @return mixed
     * @version 23 avr. 2020	: APE	- Création.
     */
    public function actionAjaxcreate() {
    	$request = Yii::$app->request;
    	
    	
    	// AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		
    		// CONSTRUCTION DU MODEL
    		$post	= $request->post();
    		$model 	= new Capteur();
    		
    		// Initialiastion des attributs avec les valeurs passées en paramètre
    		foreach( $post as $name => $value) {
    			$model->setAttribute($name, $value);
    		}
    		
    		// Le retour sera au format JSON
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		// Sauve le model
    		if( $model->save() ){
    			// Renvoie le dernier ID créé
    			return ["success" 	=> "ok",
    					"lastID" 	=> $model->id,
    					];
    		} else {
    			var_dump($model->errors);
    			return ["success" => "** Oupsss, il y a eu un problème à la création du model ".$model::className()."\n", "errors" => json_encode($model->errors)];
    		}
    	}
    }

    /**
     * Creates a new Capteur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @version 16 avr. 2020	: APE	- Redirection sur la liste des Capteurs
     */
    public function actionCreate() {
        $model 		= new Capteur();

       	// Si le modèle se sauve correctement, on reviens sur la page d'index des Capteurs
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index', 'id' => $model->id]);
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
     * @version 16 avr. 2020	: APE	- Redirection sur la liste des Capteurs
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	
            return $this->redirect(['index', 'id' => $model->id]);
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
