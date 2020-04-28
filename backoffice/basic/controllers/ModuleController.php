<?php

namespace app\controllers;

use Yii;
use app\models\Module;
use app\models\ModuleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ModuleController implements the CRUD actions for Module model.
 */
class ModuleController extends Controller
{
	// _____________________________________________________________________________________________
    /**
     * {@inheritdoc}
     * @version 28 avr. 2020	: APE	- Ajout des droits d'accès.
     */
    public function behaviors(){
        return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['create', 'update', 'delete', 'updateajax'],
				'rules' => [
					[
						'allow' => true,
						'actions' => ['create', 'update', 'delete', 'updateajax'],
						'roles' => ['@'],	// Authenticated users | (?) for anonymous user
					],
				],
			],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Module models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ModuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Module model.
     * @param string $id
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
     * Creates a new Module model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * 
     * 	@version 16 avr. 2020	: APE	- Redirection sur la page des modules en ouvrant le nouveau module.
     */
    public function actionCreate() {
        $model = new Module();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->identifiantReseau]);
        	return $this->redirect(['index', 'idModule' => $model->identifiantReseau]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Module model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * 
     * @version 16 avr. 2020	: APE	- Redirection sur la page des modules en ouvrant le nouveau module.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->identifiantReseau]);
        	return $this->redirect(['index', 'idModule' => $model->identifiantReseau]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    
    /**
     * Updates an existing Module model with an AJAX requet.
     * @param string $id
     * @return json array
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateajax() {
    	$request = Yii::$app->request;
    	
    	// UPDATE FAIT À L'AIDE D'UNE REQUÈTE AJAX -------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		$post 	= $request->post();
	    	$model 	= $this->findModel($post['id']);
	    	
	    	// Récupération de l'attribut à mettre à jour
	    	$attributeName = $post['attribute'];
	    	
	    	// Récupération de la valeur de l'attribut
	    	$value 	= $post['value']; 
	    	
	    	// Mise à jour de l'attribut
	    	$model->setAttribute($attributeName, $value);
	    	
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

    /**
     * Deletes an existing Module model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Module model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Module the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Module::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
