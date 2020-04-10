<?php

namespace app\controllers;

use Yii;
use app\models\Relmodulecapteur;
use app\models\RelmodulecapteurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\bootstrap\ActiveForm;
use yii\base\Response;

/**
 * RelmodulecapteurController implements the CRUD actions for Relmodulecapteur model.
 */
class RelmodulecapteurController extends Controller
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
     * Lists all Relmodulecapteur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RelmodulecapteurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Relmodulecapteur model.
     * @param string $idModule
     * @param integer $idCapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idModule, $idCapteur)
    {
        return $this->render('view', [
            'model' => $this->findModel($idModule, $idCapteur),
        ]);
    }

    /**
     * Creates a new Relmodulecapteur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Relmodulecapteur();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    // _____________________________________________________________________________________________
    /**
     * Updates an existing Relmodulecapteur model with ajax request.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $idModule
     * @param integer $idCapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateajax() {
    	$request = Yii::$app->request;
    	
        
        // UPDATE FAIT À L'AIDE D'UNE REQUÈTE AJAX -------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		$post = $request->post();
    		
    		$model = $this->findModel($post['idmodule'], $post['idcapteur']);
    		
    		// Extraction des coordonnées de la saisie
    		list($model->x,$model->y,$model->z) 	= explode(",",$post['value'],3);
    		
        	// Le retour sera au format JSON
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        	
    		// Sauve le model
    		if( $model->save() ){
	        	return ["success" => "ok"];
    		} else {
	        	return ["error" => "ok"];
    		}
        }
    }

    // _____________________________________________________________________________________________
    /**
     * Updates an existing Relmodulecapteur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $idModule
     * @param integer $idCapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idModule, $idCapteur) {
        $model = $this->findModel($idModule, $idCapteur);
                
        // UPDATE À PARTIR DE LA PAGE WEB (EN POST) ------------------------------------------------
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Relmodulecapteur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $idModule
     * @param integer $idCapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idModule, $idCapteur)
    {
        $this->findModel($idModule, $idCapteur)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Relmodulecapteur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $idModule
     * @param integer $idCapteur
     * @return Relmodulecapteur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idModule, $idCapteur)
    {
        if (($model = Relmodulecapteur::findOne(['idModule' => $idModule, 'idCapteur' => $idCapteur])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
