<?php

namespace app\controllers;

use Yii;
use app\models\Localisationmodule;
use app\models\LocalisationmoduleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * LocalisationmoduleController implements the CRUD actions for Localisationmodule model.
 */
class LocalisationmoduleController extends Controller
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
     * Lists all Localisationmodule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocalisationmoduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Localisationmodule model.
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
     * Creates a new Localisationmodule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @version 27 avr. 2020	: APE	- Redirection sur l'Url précédente ( doit être initialisée avec Url::remember() )
     */
    public function actionCreate()
    {
        $model = new Localisationmodule();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
        	return $this->redirect([Url::previous()]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Localisationmodule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @version 27 avr. 2020	: APE	- Redirection sur l'Url précédente ( doit être initialisée avec Url::remember() )
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//             return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect([Url::previous()]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Localisationmodule model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @version 27 avr. 2020	: APE	- Redirection sur l'Url précédente ( doit être initialisée avec Url::remember() )
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect([Url::previous()]);
//         return $this->redirect(['index']);
    }

    /**
     * Finds the Localisationmodule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Localisationmodule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Localisationmodule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
