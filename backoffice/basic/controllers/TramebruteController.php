<?php
namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\VerbFilter;

class TramebruteController extends ActiveController
{
	public $modelClass = 'app\models\Tramebrute';
	
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['verbFilter'] = [
				'class' => VerbFilter::className(),
				'actions' => $this->verbs(),
		];
		
		return $behaviors;
	}
	
	protected function verbs() {
		$verbs = parent::verbs();
		$verbs =  [
				'index' => ['GET', 'POST', 'HEAD'],
				'view' => ['GET', 'HEAD'],
				'create' => ['POST'],
				'update' => ['PUT', 'PATCH'],
				'anyOtherAction' => ['DELETE'],
		];
		return $verbs;
	}
	
}