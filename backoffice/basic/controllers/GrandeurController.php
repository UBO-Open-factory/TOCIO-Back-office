<?php

namespace app\controllers;

use Yii;
use app\models\Grandeur;
use app\models\GrandeurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Codeception\Lib\Connector\Yii2;
use PHPUnit\Framework\Constraint\Count;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\TableSearch;
use yii\data\Sort;
use app\models\Tablemesure;
use app\models\TablemesureSearch;

/**
 * GrandeurController implements the CRUD actions for Grandeur model.
 * 
 * 	@file GrandeurController.php
 */
class GrandeurController extends Controller{
	
	// _____________________________________________________________________________________________
	/**
	 * {@inheritdoc}
	 * @version 28 avr. 2020	: APE	- Ajout des droits d'accès.
	 */
	public function behaviors(){
		return [
			'access' => [
					'class' =>AccessControl::className(),
					'only' => ['create', 'update', 'delete'],
					'rules' => [
							[
									'allow' => true,
									'actions' => ['create', 'update', 'delete'],
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

	// _____________________________________________________________________________________________
	/**
	 * Display the data from the table which name is tablename in the current model.
	 *
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView( $id ) {
		
		// Find current model
		$model = $this->findModel( $id );
		
		
		// Construct a dataprovider to display result
		$searchModel = new TablemesureSearch();
		$searchModel->setTableName($model->tablename);
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render( 'view', [ 
			'model' 		=> $model,
			'searchModel' 	=> $searchModel,
            'dataProvider' 	=> $dataProvider,
		] );
	}
	
	
	
	

    /**
     * Creates a new Grandeur model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Grandeur();

        // SI LA SAISIE EST VALIDE
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	// NETTOYAGE DU FORMATTAGE SAISIE
        	$this->_FormattageFormatCapteur($model);
        	
        	
        	// FORMATTAGE DE LA NATURE (premiere caractère en majuscule)
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
	        	return $this->redirect(['index', 'id' => $model->id]);
	        		
	        	
	        	
      		// LE NOM DE CETTE TABLE EXISTE DÉJÀ ...................................................
        	} else {
        		$model->addError("nature", "Une Grandeur avec ce nom existe déjà. Impossible de la re-créer.");
//         		// LA TABLE EST VIDE, ON LA SUPPRIME & ON CREER LA MESURE
//         		if( $this->_getNbMesureFromTableMesure($model->tablename) == 0){
        			
//         			// Suppression de la table des mesures
//         			$this->_deleteTableMesure($model->tablename);
        			
//         			// Création de la Grandeur
//         			$model->save();
        			
//         		} else {
// 	        		// LA TABLE N'EST PAS VIDE, ON AFFICHE UNE ERREUR
// 	        		// Affiche un message sur la page de la saisie.
// 	        		$model->addError('nature', "Impossible de créer cette Grandeur car La table des mesures prévus <".$model->tablename."> existe déjà (et n'est pas vide).");
//         		}
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
    public function actionUpdate($id){
//         $model = $this->findModel($id);

//         if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//         	// NETTOYAGE DU FORMATTAGE SAISIE
//         	$model->formatCapteur = str_replace(".", ",", $model->formatCapteur);
        	
        	
//         	// FORMATTAGE DE LA NATURE (premier caractère en majuscule )
//         	$model->nature = ucfirst( $model->nature );
        	
        	
//         	// ADAPTATION DU TYPE DE LA TABLE
//         	$l_STR_Requete = "ALTER TABLE `".$model->tablename."` CHANGE `valeur` `valeur` ".$model->type." NOT NULL;";
//         	Yii::$app->db->createCommand($l_STR_Requete)
// 	        	->execute();
        	
        	
//         	// SAUVEGARDE LA SAISIE
//         	$model->save();
        	
//         	// REDIRECTION 
//             return $this->redirect(['index', 'id' => $model->id]);
//         }

//         return $this->render('update', [
//             'model' => $model,
//         ]);
    }

    
    
    /**
     * Deletes an existing Grandeur model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @version 24 avr. 2020	: APE	- Si la table des mesures associée est vide, on la supprime.
     */
    public function actionDelete($id) {
    	
    	// SUPPRESSION DE LA TABLES DES MESURES ASSOCIÉE (SI ELLE EST VIDE ) -----------------------
    	$model = $this->findModel($id);
    	if( $this->_getNbMesureFromTableMesure($model->tablename) == 0){
     		$this->_deleteTableMesure($model->tablename);
    	}
    	
    	// SUPPRESSION DE LA GRANDEUR DANS LA TABLE ------------------------------------------------
     	$model->delete();

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
     * Supprime la table des mesures dont le nom est passé en paramètre.
     * @param string $p_STR_NomTable : Le nom de la table des mesures.
     * @return number
     */
    private function _deleteTableMesure($p_STR_NomTable){
    	return \Yii::$app->db->createCommand("DROP TABLE {{".$p_STR_NomTable."}}")
							->execute();
    }
    
    
    
    // ---------------------------------------------------------------------------------------------
    /**
     * Vérifie qu'un nom de table de mesure existe dans la base.
     * Les nom de ces tables commencent pas tm_
     * @param $p_STR_NomTable
     * @return BOOLEAN
     * @todo il y a certainnement matière à optimiser cette function.
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
     * Renvoie le nombre de données dans une table de mesure passée en paramètre.
     * @param string $p_STR_NomTable	Le nom de la table des mesures.
     * 	@version 24 avr. 2020	: APE	- Création.
     */
    private function _getNbMesureFromTableMesure($p_STR_NomTable){
    	return \Yii::$app->db->createCommand("SELECT COUNT(*) FROM {{".$p_STR_NomTable."}}")
    							->queryScalar();
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
