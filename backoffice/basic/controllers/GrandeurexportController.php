<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\GrandeurExportForm;

class GrandeurexportController extends Controller {
	// _____________________________________________________________________________________________
	/**
	 *
	 * {@inheritdoc}
	 * @version 28 avr. 2020 : APE - Ajout des droits d'accès.
	 */
	public function behaviors() {
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => [
								'create',
								'update',
								'delete' ],
						'rules' => [
								[
										'allow' => true,
										'actions' => [
												'create',
												'update',
												'delete' ],
										'roles' => [
												'@' ] // Authenticated users | (?) for anonymous user
								] ] ],
				'verbs' => [
						'class' => VerbFilter::className(),
						'actions' => [
								'delete' => [
										'POST' ] ] ] ];
	}
	
}