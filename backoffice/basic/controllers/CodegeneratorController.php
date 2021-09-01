<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Codeception\Lib\Connector\Yii2;
use app\models\Grandeur;
use app\models\GrandeurSearch;

/**
 * GrafanaController implements the CRUD actions for Grandeur model.
 *
 *	@file CodegeneratorController.php
 */
class CodegeneratorController extends Controller {

	// -----------------------------------------------------------------------------------
	/**
	 *	Return the generated code in JSON format to by inserted in Grafana.
	 *
	 * @param integer $id
	 *        	: l'ID de la grandeur dont on veut la définition.
	 * @return string JSON
	 */
	public function actionGrafana( $id ) {

		// Search for the Grandeur with it's ID
		$model = Grandeur::findOne( $id );
		
		// Récupération des autres Grandeurs disponibles
		$searchModel = new GrandeurSearch(); 
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		// Get the JSON code for pannel
		$panell = $this->_getGrafanaPanellCode( $model->id, $model->tablename, $model->nature );

		return $this->render('index', [
				//'code' => json_encode($panell, JSON_PRETTY_PRINT),
				'code' => json_encode($panell) . ",",
				'tablename' => $model->tablename,
				'title' => $model->nature,
				'dataProvider' => $dataProvider
		]);
	}

	// -----------------------------------------------------------------------------------
	/**
	 * Return the json code to generate the Grafana pannel.
	 *
	 * @param integer $id
	 *        	: GrandeurID
	 * @param string $tableName
	 *        	: Table name of the Grandeur
	 * @param string $title
	 *        	: Pannel's name
	 * @return array in JSON format
	 *        
	 * @see https://grafana.com/docs/grafana/latest/dashboards/json-model/
	 * @see https://www.jsontophp.com/
	 */
	private function _getGrafanaPanellCode( $id, $tableName, $title ) {
		$result = [
			"datasource" => null,
			"fieldConfig" => [
				"defaults" => [
					"color" => [
						"mode" => "palette-classic"
					],
					"custom" => [
						"axisLabel" => "",
						"axisPlacement" => "auto",
						"barAlignment" => 0,
						"drawStyle" => "line",
						"fillOpacity" => 0,
						"gradientMode" => "none",
						"hideFrom" => [
							"legend" => false,
							"tooltip" => false,
							"viz" => false
							],
						"lineInterpolation" => "linear",
						"lineWidth" => 1,
						"pointSize" => 5,
						"scaleDistribution" => ["type" => "linear"],
						"showPoints" => "auto",
						"spanNulls" => false,
						"stacking" => [
							"group" => "A",
							"mode" => "none"
						],
						"thresholdsStyle" => ["mode" => "off"]
					],
					"mappings" => [],
					"thresholds" => [
						"mode" => "absolute",
						"steps" => [
							[	"color" => "green",
								"value" => null
							],
							[	"color" => "red",
								"value" => 80
							]
						]
					]
				],
				"overrides" => []
			],
			"gridPos" => [
				"h" => 9,
				"w" => 23,
				"x" => 0,
				"y" => 0
			],
			"id" => $id,
			"options" => [
				"legend" => [
					"calcs" => [],
					"displayMode" => "list",
					"placement" => "bottom"
				],
				"tooltip" => [
					"mode" => "single"
				]
			],
			"targets" => [
				[
					"format" => "time_series",
					"group" => [
					],
					"metricColumn" => "valeur",
					"rawQuery" => false,
					"rawSql" => "SELECT  timestamp AS \"time\",  valeur AS metric,  valeur FROM ".$tableName." ORDER BY timestamp",
					"refId" => "A",
					"select" => [
						[
							[
								"params" => [
									"valeur"
								],
								"type" => "column"
							]
						]
					],
					"table" => $tableName,
					"timeColumn" => "timestamp",
					"timeColumnType" => "timestamp",
					"where" => []
				]
			],
			"title" => $title,
			"type" => "timeseries"
		];

		return $result;
	}
}
