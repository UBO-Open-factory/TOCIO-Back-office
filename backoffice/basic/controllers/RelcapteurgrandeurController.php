<?php

namespace app\controllers;

use Yii;
use app\models\Relcapteurgrandeur;
use app\models\RelcapteurgrandeurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RelcapteurgrandeurController implements the CRUD actions for Relcapteurgrandeur model.
 */
class RelcapteurgrandeurController extends Controller
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
     * Lists all Relcapteurgrandeur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RelcapteurgrandeurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Relcapteurgrandeur model.
     * @param integer $idCapteur
     * @param integer $idGrandeur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idCapteur, $idGrandeur)
    {
        return $this->render('view', [
            'model' => $this->findModel($idCapteur, $idGrandeur),
        ]);
    }

    // _____________________________________________________________________________________________
    /**
     * Creates a new Relcapteurgrandeur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * 	@version 23 avr. 2020	: APE	- Création.
     */
    public function actionAjaxcreate() {
    	$request = Yii::$app->request;
    	
    	
    	// AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		
    		
    		// CONSTRUCTION DU MODEL
    		$post	= $request->post();
    		$model 	= new Relcapteurgrandeur();

    		
    		
    		// Initialiastion des attributs avec les valeurs passées en paramètre
    		foreach( $post as $name => $value) {
    			$model->setAttribute($name, $value);
    		}
    		
    		
    		// Le retour sera au format JSON
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		// Sauve le model
    		if( $model->save() ){
    			return ["success" => "ok", "url" => "capteur/update?id=".$post['idCapteur']];
    		} else {
    			
    			return ["success" => "** Oupsss, il y a eu un problème à la création du model ".$model::className()."\n", 
    					"errors" => json_encode($model->errors)];
    		}
    	}
    }
    
    
    /**
     * Creates a new Relcapteurgrandeur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Relcapteurgrandeur();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idCapteur' => $model->idCapteur, 'idGrandeur' => $model->idGrandeur]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Relcapteurgrandeur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $idCapteur
     * @param integer $idGrandeur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idCapteur, $idGrandeur)
    {
        $model = $this->findModel($idCapteur, $idGrandeur);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idCapteur' => $model->idCapteur, 'idGrandeur' => $model->idGrandeur]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Relcapteurgrandeur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idCapteur
     * @param integer $idGrandeur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * 	@version 24 avr. 2020	: APE	- Redirection sur la page d'update d'un capteur
     */
    public function actionDelete($idCapteur, $idGrandeur) {
        $this->findModel($idCapteur, $idGrandeur)->delete();

//         return $this->redirect(['index']);

        return $this->redirect(['/capteur/update?id='. $idCapteur]);
    }

    /**
     * Finds the Relcapteurgrandeur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idCapteur
     * @param integer $idGrandeur
     * @return Relcapteurgrandeur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idCapteur, $idGrandeur)
    {
        if (($model = Relcapteurgrandeur::findOne(['idCapteur' => $idCapteur, 'idGrandeur' => $idGrandeur])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
