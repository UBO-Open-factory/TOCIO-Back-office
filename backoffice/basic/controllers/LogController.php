<?php

namespace app\controllers;

use Yii;
use app\models\Log;
use app\models\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
{
	// _____________________________________________________________________________________________
	/**
	 * {@inheritdoc}
	 * @version 28 avr. 2020	: APE	- Ajout des droits d'accès.
	 */
	public function behaviors(){
		return [
			'access' => [
					'class' =>AccessControl::className(),
					'only' => ['create', 'update', 'delete', 'index'],
					'rules' => [
							[
									'allow' => true,
									'actions' => ['create', 'update', 'delete', 'index'],
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

	// ___________________________________________________________________________________
	/**
	 * Lists all CSV import Logs.
	 * CSV import are made with a curl request and Logs files are in a special directory (next to imported files)
	 *
	 * @return mixed
	 */
	public function actionLogimports() {

		// Récupération de tout les fichiers dans le répertoire d'archives des imports CSV
		$files = FileHelper::findFiles( Yii::$app->basePath.Yii::getAlias( "@CSVIimportDirectory" ) );
		
		
		// s'il n'existe pas de fichiers de logs (le répertoire est vide)
		if( count( $files ) == 0 ){
			// Redirection sur la page d'acceuil
			$this->redirect("/");
		}
		
		// Transformation de la liste des fichiers en structure pour affichage dans la page 
		// sous forme d'un dataprovider
		$l_TAB_Files = [];
		foreach ($files as $file){
			// extraction de la date du nom du fichier (moduleID_AAAAMMJJ_HH.csv.log ou moduleID_AAAAMMJJ_HH.csv)
			$date = substr(strrchr($file,'.'),1) =="log" ? substr($file, -8 -11, 11) : substr($file, -4 -11, 11);
			$date = date("Y-m-d H:i", mktime(substr($date, -2,2), 0, 0,
									substr($date,4,2 ),	// month
									substr($date,6,2 ),	// day
									substr($date,0,4 )	// year
					));
			
			$l_TAB_Files[] = [
					'file'	=> $file,
					'url'	=> Url::to([basename(Yii::getAlias( "@CSVIimportDirectory" ))."/".basename($file)]),
					'name'	=> basename($file),
					'type'	=> substr(strrchr($file,'.'),1) =="log" ? "Log" : "Source",
					'date'	=> $date,
			];
		}


		// Envoie des data à la vue
		$dataProvider = new ArrayDataProvider([
				'allModels'	=>  $l_TAB_Files,
		]);
		return $this->render( 'logimports', [
				'dataProvider' => $dataProvider ] );
	}
	
	
	// ___________________________________________________________________________________
	/**
	 * Renvoie s'il existe des fichiers de log dans le répertoire des imports des fichiers CSV
	 * @return boolean 
	 * 
	 */
	public function ExisteLogimports(){
		// Récupération de tout les fichiers dans le répertoire d'archives des imports CSV
		$files = FileHelper::findFiles( Yii::$app->basePath.Yii::getAlias( "@CSVIimportDirectory" ) );
		
		return count( $files ) != 0;
	}
	

    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Log model.
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
     * Creates a new Log model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Log();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Log model.
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
     * Deletes an existing Log model.
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
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
