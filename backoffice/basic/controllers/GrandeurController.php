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
 * 
 * 	@file GrandeurController.php
 */
class GrandeurController extends Controller{
	
	
	
	
	
	// ---------------------------------------------------------------------------------------------
	/**
	 * Renvoie la liste des grandeurs au format JSON.
	 *
	 * @return string JSON
	 * @see https://www.yiiframework.com/doc/guide/2.0/fr/output-data-providers
	 */
	public function actionGetgrandeurs(){
		// Le format de l'affichage du modele serra en JSON
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		// Renvoie tout ce qui est dans la table "Grandeur"
		return Grandeur::find()->all();
	}
	
	
	
	// ---------------------------------------------------------------------------------------------
	/**
	 * Renvoie une Grandeur dont l'ID est passé en paramètre au format JSON.
	 *
	 * @param integer $id : l'ID de la grandeur dont on veut la définition.
	 * @return string  JSON
	 */
	public function actionGetgrandeur($id){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		return Grandeur::find()
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
     * @todo Afficher un message si le coupple (Nature,Type) existent déjà.
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
        	// NETTOYAGE DU FORMATTAGE SAISIE
        	$this->_FormattageFormatCapteur($model);
        	
        	
        	// FORMATTAGE DE LA NATURE (premiere caractère en majuscule )
        	$model->nature = ucfirst( $model->nature );
        	
        	
        	// CONSTRUCTION DU NOM DE TABLE SAISIE
        	$this->_ConstruitNomTable($model);
        	
        	
        	// SI LE NOM DE TABLE N'EST PAS UTILISÉ DANS LA BASE ...................................
        	if (! $this->_tableMesureExiste($model->tablename)) {
        		// REQUETE DE CREATION DE LA TABLE
        		$this->_createTableMesure($model);
        		
	        		
	        	// SAUVEGARDE LA SAISIE
	        	$model->save();

	        	
	        	// ON RETOURNE SUR LA LISTE
	        	return $this->redirect(['view', 'id' => $model->id]);
	        		
	        	
	        	
      		// LE NOM DE CETTE TABLE EXISTE DÉJÀ ...................................................
        	} else {
        		// Affiche un message sur la page de la saisie.
        		$model->addError('nature', "La table <".$model->tablename."> existe déjà");
        		
        		// @todo traiter le cas de l'existance de la table pour cette grandeur
        		
        	}
        	
        	
        }
        return $this->render('create', [
        		'model' => $model,
        ]);
    }
    
    
    

    

    /**
     * Updates an existing Grandeur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	// NETTOYAGE DU FORMATTAGE SAISIE
        	$model->formatCapteur = str_replace(".", ",", $model->formatCapteur);
        	
        	
        	// FORMATTAGE DE LA NATURE (premier caractère en majuscule )
        	$model->nature = ucfirst( $model->nature );
        	
        	
        	// ADAPTATION DU TYPE DE LA TABLE
        	$l_STR_Requete = "ALTER TABLE `".$model->tablename."` CHANGE `valeur` `valeur` ".$model->type." NOT NULL;";
        	Yii::$app->db->createCommand($l_STR_Requete)
	        	->execute();
        	
        	
        	// SAUVEGARDE LA SAISIE
        	$model->save();
        	
        	// REDIRECTION 
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
     * Fait une requète MySQL pour créer une table de mesure pour le modèle et renvoie le résultat.
     * @param $model
     * @return Yii de la requète
     */
    private function _createTableMesure($model){
    	$l_STR_requete = "CREATE TABLE `".$model->tablename."` (
							  `id` int(11) NOT NULL,
							  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
							  `valeur` ".$model->type." NOT NULL,
							  `posX` int(3) NOT NULL,
							  `posY` int(3) NOT NULL,
							  `posZ` int(3) NOT NULL,
							  `identifiantModule` varchar(50) NOT NULL
							) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table des mesures :nom';
							ALTER TABLE ".$model->tablename."
								ADD PRIMARY KEY (`id`),
  								ADD UNIQUE KEY `id` (`id`);
							COMMIT;
							ALTER TABLE ".$model->tablename."
								MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
							COMMIT;";
    	// Bind des valeurs saisie dans la requète + Creation de la table
    	return Yii::$app->db->createCommand($l_STR_requete)
    	->bindValue(':nom', $model->nature)
    	->execute();
    }
    
    
    // ---------------------------------------------------------------------------------------------
    /**
     * Vérifie qu'un nom de table de mesure existe dans la base.
     * Les nom de ces tables commencent pas tm_
     * @param $p_STR_NomTable
     * @return BOOLEAN
     */
    private function _tableMesureExiste($p_STR_NomTable){
    	// Récupération des noms de tables commencant par tm_
    	$l_TAB_Noms	= array();
    	$l_STR_requete = "SHOW TABLES LIKE 'tm_%'";
    	$l_TAB_NomTables	= Yii::$app->db->createCommand($l_STR_requete)->queryAll();
    	
    	
    	
    	
    	// Construction d'un tableau avec les noms de table.
    	foreach( $l_TAB_NomTables as $l_TAB_NomTable){
    		foreach( $l_TAB_NomTable as $key => $l_STR_Nom){
    			$l_TAB_Noms[]= $l_STR_Nom;
    		}
    	}


    	return in_array($p_STR_NomTable, $l_TAB_Noms);
    }
    
    
    // ---------------------------------------------------------------------------------------------
    /**
     * Vérifie le formattage du format de la mesure d'une Grandeur.
     * @param unknown $model
     */
    private function _FormattageFormatCapteur($model){
    	$model->formatCapteur = str_replace(".", ",", $model->formatCapteur);
    }
    
    
    
    // ---------------------------------------------------------------------------------------------
    /**
     * Génère le nom de la table en fonction de la nature de la Grandeur.
     * @param $model : ARRAY contenant le modele d'une Grandeur.
     */
    private function _ConstruitNomTable($model){
    	// NETTOYAGE DU NOM DE TABLE SAISIE
    	$l_STR_NomClean	= str_replace("'/", "", strtolower($model->nature));
    	$l_STR_NomClean = preg_replace('#[^A-Za-z0-9]+#', '', $l_STR_NomClean);
    	$l_STR_NomClean = "tm_".$this->_stripAccents($l_STR_NomClean);
    	
    	$model->tablename = $l_STR_NomClean;
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
