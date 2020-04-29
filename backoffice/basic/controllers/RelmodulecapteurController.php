<?php

namespace app\controllers;

use Yii;
use app\models\Relmodulecapteur;
use app\models\RelmodulecapteurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Response;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\filters\AccessControl;

/**
 * RelmodulecapteurController implements the CRUD actions for Relmodulecapteur model.
 */
class RelmodulecapteurController extends Controller
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
					'only' => ['create', 'update', 'delete', 'updateorderajax', 'attacheajax', 'updateajax'],
					'rules' => [
							[
									'allow' => true,
									'actions' => ['create', 'update', 'delete', 'updateorderajax', 'attacheajax', 'updateajax'],
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
     * @param string $nomcapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idModule, $idCapteur, $nomcapteur)
    {
        return $this->render('view', [
            'model' => $this->findModel($idModule, $idCapteur, $nomcapteur),
        ]);
    }

    /**
     * Creates a new Relmodulecapteur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @version 16 avr. 2020	: APE	- Redirection sur la page des modules
     */
    public function actionCreate() {
        $model = new Relmodulecapteur();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	return $this->redirect(['module/index', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur, 'nomcapteur' => $model->nomcapteur]);
        	
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    
    // _____________________________________________________________________________________________
    /**
     * Met a jour l'ordre des capteurs dans un module.
     * On va recevoir une chaine en POST de la forme : 123456789|2,123456789|3,123456789|1
     * Elle donne l'ordre des tuples (idModule|idCapteur) dans la table rel_modulecapteur.
     * 
     * @return string[]
     */
    public function actionUpdateorderajax(){
    	$request = Yii::$app->request;
    	
    	
    	// UPDATE FAIT À L'AIDE D'UNE REQUÈTE AJAX -------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		$post = $request->post();
    		
    		$l_TAB_ordres = $post['ordre'];	// un truc du genre 123456789|2,123456789|3,123456789|1,
    		
    		
    		// Mise à jour de l'ordre
    		foreach( $l_TAB_ordres as $ordre => $tuple){
    			// Extraction des id de module et capteur
    			list( $idModule, $idCapteur, $nomcapteur, $poubelle ) 	= explode("|", $tuple, 4);

    			// recherche du modele
    			$model = $this->findModel($idModule, $idCapteur, Html::decode($nomcapteur));
    			
    			// Mise à jour de l'ordre & Sauvegarde du module
    			$model->updateAttributes(['ordre' => $ordre]);
    		}
    	}
    }
    
    
    // _____________________________________________________________________________________________
    /**
     * Création d'une relation entre un module et un capteur.
     * @param string $idModule
     * @param integer $idCapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAttacheajax() {
    	$request = Yii::$app->request;
    	
        
        // AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		
    		

    		// CONSTRUCTION DU MODEL
    		$post	= $request->post();
    		$model 	= new Relmodulecapteur();
    		
    		// On recherche si le capteur existe déjà avec ce nom
    		$relModuleCapteurs 	= Relmodulecapteur::findAll(['idModule' => $post['idModule'], 'idCapteur' => $post['idCapteur']]);
    		$l_TAB_NomCapteurs	= [];
    		foreach( $relModuleCapteurs as $l_OBJ_RelModuleCapteur){
    			$l_TAB_NomCapteurs[]	= $l_OBJ_RelModuleCapteur->nomcapteur;
    		}
    		if ( in_array($post['nomcapteur'], $l_TAB_NomCapteurs) ){
    			$post['nomcapteur']	= $post['nomcapteur']. " Ajouté le ". date("Y/m/d H:i:s", time());
    		}
    		
    		
    		
    		// Initialiastion des attributs avec les valeurs passées en paramètre 
    		foreach( $post as $name => $value) {
    			$model->setAttribute($name, $value);
    		}
    		
    		
        	// Le retour sera au format JSON
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		// Sauve le model
    		if( $model->save() ){
    			return ["success" => "ok", "url" => "module/index?idModule=".$post['idModule']];
    		} else {
    			
    			return ["success" => "** Oupsss, il y a eu un problème à la création du model ".$model::className()."\n", "errors" => json_encode($model->errors)];
    		}
        }
    }
    
    // _____________________________________________________________________________________________
    /**
     * Updates coordinate on an existing Relmodulecapteur model with ajax request.
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
	        	return ["success" => "** Oupsss, il y a eu un problème lors de la mise à jour de ".$model::className()];
    		}
        }
    }

    // _____________________________________________________________________________________________
    /**
     * Updates an existing Relmodulecapteur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $idModule
     * @param integer $idCapteur
     * @param string $nomcapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @version 25 avr. 2020	: APE	- Rerdirection sur l'Url précédente ( doit être initialisée avec Url::remember() )
     */
    public function actionUpdate($idModule, $idCapteur, $nomcapteur) {
    	$model = $this->findModel($idModule, $idCapteur, $nomcapteur);
                
        // UPDATE À PARTIR DE LA PAGE WEB (EN POST) ------------------------------------------------
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//         	return $this->redirect(['view', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur, 'nomcapteur' => $model->nomcapteur]);
        	return $this->redirect([Url::previous(), 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur, 'nomcapteur' => $model->nomcapteur]);
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
     * @param string $nomcapteur
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * 
     * 	@version 16 avr. 2020	: APE	- Redirection sur la page des Modules.
     */
    public function actionDelete($idModule, $idCapteur, $nomcapteur) {
    	$this->findModel($idModule, $idCapteur, $nomcapteur)->delete();

    	return $this->redirect(['module/index', 'idModule' => $idModule]);
//         return $this->redirect(['index']);
    }

    /**
     * Finds the Relmodulecapteur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $idModule
     * @param integer $idCapteur
     * @param string $nomcapteur
     * @return Relmodulecapteur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idModule, $idCapteur, $nomcapteur)
    {
    	if (($model = Relmodulecapteur::findOne(['idModule' => $idModule, 'idCapteur' => $idCapteur, 'nomcapteur' => $nomcapteur])) !== null) {
    		return $model;
    	}

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
