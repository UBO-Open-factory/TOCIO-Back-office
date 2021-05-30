<?php

namespace app\controllers;

use Yii;
use app\models\Relcartesmethod;
use app\models\RelcartesmethodSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * RelcartesmethodController implements the CRUD actions for relcartesmethod model.
 */
class RelcartesmethodController extends Controller
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
     * Lists all relcartesmethod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new relcartesmethodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single relcartesmethod model.
     * @param integer $id_carte
     * @param integer $id_method
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_carte, $id_method)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_carte, $id_method),
        ]);
    }

    /**
     * Creates a new relcartesmethod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new relcartesmethod();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_carte' => $model->id_carte, 'id_method' => $model->id_method]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Deletes an existing relcartesmethod model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_carte
     * @param integer $id_method
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_carte, $id_method)
    {
        $this->findModel($id_carte, $id_method)->delete();

        //return $this->redirect(['index']);

        return $this->redirect(['/cartes/update?id='. $id_carte]);
    }

    // _____________________________________________________________________________________________
    /**
     * Creates a new Relcapteurgrandeur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @version 23 avr. 2020    : APE   - Création.
     * @version 2 mai 2020  : APE   - Utilisation du générateur d'Url.
     */
    public function actionAjaxcreate() 
    {
        $request = Yii::$app->request;
        
        // AJOUT FAIT À L'AIDE D'UNE REQUÈTE AJAX --------------------------------------------------
        if (Yii::$app->request->isAjax && $request->post()) 
        {
            
            // Construction du model
            $post   = $request->post();
            $model  = new Relcartesmethod();
            
            // Initialiastion des attributs avec les valeurs passées en paramètre
            foreach( $post as $name => $value) 
            {
                $model->setAttribute($name, $value);
            }
            
            // Le retour sera au format JSON
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            // Sauve le model
            if( $model->save() )
            {
                return 
                [
                    "success" => "ok", 
                    "url" => Url::to(["/cartes/update", "id" => $post['id_carte'] ])
                ];
            } 
            else 
            {
                
                return 
                [
                    "success" => "** Oupsss, il y a eu un problème à la création du model ".$model::className()."\n", 
                    "errors" => json_encode($model->errors)
                ];
            }
        }
    }

    /**
     * Finds the relcartesmethod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_carte
     * @param integer $id_method
     * @return relcartesmethod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_carte, $id_method)
    {
        if (($model = relcartesmethod::findOne(['id_carte' => $id_carte, 'id_method' => $id_method])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
