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
     * @version 7 janv. 2021    : APE    - Initialisation du numéro d'ordre du capteur dans le module.
     */
    public function actionCreate() {
        $model = new Relmodulecapteur();

        // Si on arrive a créer un module à partir des données récupérées en POST
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post()['Relmodulecapteur'];
            
            
            // ON RECHERCHE SI LE CAPTEUR EXISTE DÉJÀ AVEC CE NOM ..................................
            // ( si c'est le cas on modifie son nom )
            $l_TAB_NomCapteurs	= [];
            $relModuleCapteurs 	= Relmodulecapteur::findAll(['idModule' => $post['idModule'], 'idCapteur' => $post['idCapteur']]);
            foreach( $relModuleCapteurs as $l_OBJ_RelModuleCapteur){
                $l_TAB_NomCapteurs[]	= $l_OBJ_RelModuleCapteur->nomcapteur;
                
            }
            if ( in_array($post['nomcapteur'], $l_TAB_NomCapteurs) ){
                $post['nomcapteur']	= $post['nomcapteur']. " Ajouté le ". date("Y/m/d H:i:s", time());
            }
            
            
            // RECHERCHE LE PLUS GRAND NUMÉRO D'ORDRE ..............................................
            $l_INT_MaxOrdre 	= 0;
            foreach( Relmodulecapteur::findAll(['idModule' => $post['idModule']]) as $l_OBJ_RelModuleCapteur){
                
                // Recherche le numéro d'Ordre le plus grand
                if( $l_OBJ_RelModuleCapteur->ordre > $l_INT_MaxOrdre ){
                    $l_INT_MaxOrdre = $l_OBJ_RelModuleCapteur->ordre;
                }
            }
            // Initialisation du numéro d'ordre du capteur 
            $model->setAttribute("ordre", $l_INT_MaxOrdre+1);
            
            
            
            // SAUVEGARDE DU MODEL ...................................................................
            if ($model->save()) {
        	   return $this->redirect(['module/index', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur, 'nomcapteur' => $model->nomcapteur]);
            } else {
                return ["success" => "** Oupsss, il y a eu un problème à la création du model ".$model::className()."\n", "errors" => json_encode($model->errors)];
            }
        }

        return $this->render('create', [
            'model' => $model
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
     * 
     * 	@version 6 nov. 2020	: APE	- Le numéro d'odre n'est plus 99 par défaut, mais le plus grand.
     */
    public function actionAttacheajax() {
    	$request = Yii::$app->request;
    	
        
        // AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
    	if (Yii::$app->request->isAjax && $request->post()) {
    		
    		

    		// CONSTRUCTION DU MODEL
    		$post	= $request->post();
    		$model 	= new Relmodulecapteur();
    		
    		// ON RECHERCHE SI LE CAPTEUR EXISTE DÉJÀ AVEC CE NOM ..................................
    		// ( comme ça on modifie son nom )
    		$relModuleCapteurs 	= Relmodulecapteur::findAll(['idModule' => $post['idModule'], 'idCapteur' => $post['idCapteur']]);
    		$l_TAB_NomCapteurs	= [];
    		foreach( $relModuleCapteurs as $l_OBJ_RelModuleCapteur){
    			$l_TAB_NomCapteurs[]	= $l_OBJ_RelModuleCapteur->nomcapteur;
    			
    		}
    		if ( in_array($post['nomcapteur'], $l_TAB_NomCapteurs) ){
    			$post['nomcapteur']	= $post['nomcapteur']. " Ajouté le ". date("Y/m/d H:i:s", time());
    		}
    		
    		
    		
    		// RECHERCHE LE PLUS GRAND NUMÉRO D'ORDRE ..............................................
    		$l_INT_MaxOrdre 	= 0; 
    		foreach( Relmodulecapteur::findAll(['idModule' => $post['idModule']]) as $l_OBJ_RelModuleCapteur){
    			
    			// Recherche le numéro d'Ordre le plus grand
    			if( $l_OBJ_RelModuleCapteur->ordre > $l_INT_MaxOrdre ){
    				$l_INT_MaxOrdre = $l_OBJ_RelModuleCapteur->ordre;
    			}
    		}
    		
    		
    		
    		// INITIALIASTION DES ATTRIBUTS AVEC LES VALEURS PASSÉES EN PARAMÈTRE ..................
    		foreach( $post as $name => $value) {
    			if( $name == "ordre" and $value = 99) {
    				// Si l'ordre est bien à 99 c'est que le capteur vient d'être attaché au module.
    				// dans ce cas, on prend le numéro d'ordre le plus grand dans la base et on l'incrémente
    				// Le but est qu'il ne doit pas y avoir plusieurs capteur avec le même numéro d'ordre, mais que  
    				// l'ordre d'affichage des capteurs soit belle et bien le même que celui de leur création. 
    				$value = $l_INT_MaxOrdre +1;
    			}
    			$model->setAttribute($name, $value);
    		}
    		
    		
        	// Le retour sera au format JSON
    		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    		
    		
    		// SAUVE LE MODEL ......................................................................
    		if( $model->save() ){
    			return ["success" => "ok", "url" => Url::to(["/module/index", "idModule" => $post['idModule']])];
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
     * @version 6 nov. 2020	: APE	- Redirection sur la page des modules (car problème avec les uRL behind proxy sinon)
     */
    public function actionUpdate($idModule, $idCapteur, $nomcapteur) {
    	$model = $this->findModel($idModule, $idCapteur, $nomcapteur);
                
        // UPDATE À PARTIR DE LA PAGE WEB (EN POST) ------------------------------------------------
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//         	return $this->redirect(['view', 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur, 'nomcapteur' => $model->nomcapteur]);
//         	return $this->redirect([Url::previous(), 'idModule' => $model->idModule, 'idCapteur' => $model->idCapteur, 'nomcapteur' => $model->nomcapteur]);
        	return $this->redirect(['module/index', 'idModule' => $idModule]);
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
