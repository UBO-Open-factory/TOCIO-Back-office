<?php

namespace app\controllers;

use Yii;
use app\models\Grandeur;
use app\models\GrandeurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GrandeurController implements the CRUD actions for Grandeur model.
 */
class GrandeurController extends Controller
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
     * Lists all Grandeur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GrandeurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Grandeur model.
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
     * Creates a new Grandeur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @todo Afficher un message si la Grandeur et le Type existent déjà.
     */
    public function actionCreate() {
        $model = new Grandeur();
        /*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
        */
        // SI LA SAISIE EST VALIDE
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	// Nettoyage du nom de table saisie
        	$l_STR_NomClean	= str_replace("'/", "", strtolower($model->nature));
        	$l_STR_NomClean = preg_replace('#[^A-Za-z0-9]+#', '', $l_STR_NomClean);
        	$l_STR_NomClean = "tm_".$this->_stripAccents($l_STR_NomClean);
        	$model->tablename = $l_STR_NomClean;
        	
        	// ON VÉRIFIE QUE LE NOM DE LA TABLE N'EXISTE PAS DANS LA BASE
        	// Récupération des noms de tables.
        	$l_TAB_Noms	= array();
        	$l_STR_requete = "SHOW TABLES LIKE 'tm_%'";
        	$l_TAB_NomTables	= Yii::$app->db->createCommand($l_STR_requete)->queryAll();
        	foreach( $l_TAB_NomTables as $l_TAB_NomTable){
        		foreach( $l_TAB_NomTable as $key => $l_STR_Nom){
        			$l_TAB_Noms[]= $l_STR_Nom;
        		}
        	}
        	// Si le nom de table n'est pas utilisé ................................................
        	if( !in_array($model->tablename, $l_TAB_Noms)){
        		// Requete de creation de la table
        		$l_STR_requete = "CREATE TABLE `".$l_STR_NomClean."` (
							  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
							  `valeur` ".$model->type." NOT NULL,
							  `posX` int(3) NOT NULL,
							  `posY` int(3) NOT NULL,
							  `posZ` int(3) NOT NULL,
							  `idModule` int(3) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table des mesures :nom';
							ALTER TABLE ".$l_STR_NomClean."
							  ADD PRIMARY KEY (`timestamp`),
							  ADD KEY `timestamp` (`timestamp`);
							COMMIT;";
        		// Bind des valeurs saisie dans la requète + Creation de la table
        		Yii::$app->db->createCommand($l_STR_requete)
	        		->bindValue(':nom', $model->nature)
	        		->execute();

	        		
	        	// SAUVEGARDE LA SAISIE
	        	$model->save();
	        	
	        	// ON RETOURNE SUR LA LISTE
	        	return $this->redirect(['view', 'id' => $model->id]);
	        		
      		// Le nom de cette table existe déjà ...................................................
        	} else {
        		// Affiche un message sur la page de la saisie.
        		
        	}
        	
        	
        }
        return $this->render('create', [
        		'model' => $model,
        ]);
    }

    /**
     * Updates an existing Grandeur model.
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
     * Deletes an existing Grandeur model.
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
     * Finds the Grandeur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grandeur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grandeur::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
    
    
    
    // ---------------------------------------------------------------------------------------------
    /**
     * Supprimer toutles accents dan sune chaine de caractère.
     * @param $string à nettoyer
     * @return string sans accents.
     */
    private function _stripAccents($string){
    	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
}
