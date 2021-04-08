<?php

namespace app\models;

use yii\base\Model;

class GrandeurExportForm extends Model {
	// Le nom de la table des Mesures
	public $tableName;

	// Unit of cumul (hour, minute, day, etc...)
	public $cumulBy;
	
	// Module name
	public $moduleName;
	
	// date de début à l'aquelle faire l'export des données
	public $dateStart;
	
	// date de fin à l'aquelle faire l'export des données
	public $dateEnd;
	
	public function rules() {
		return [[	['cumulBy', 'tableName', 'moduleName', 'dateStart', 'dateEnd'], 'string',
		]];
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Ajoute un labelle lisible par un humain pour les différents champs du model UploadImageForm
	 * {@inheritDoc}
	 * @see \yii\base\Model::attributeLabels()
	 */
	public function attributeLabels(){
		return [
				'tableName' 	=> 'Grandeur a exporter',
				'cumulBy' 		=> 'Cumul par',
				'moduleName' 	=> 'Filtre par Module',
				'dateStart' 	=> 'Date de début',
				'dateEnd' 		=> 'Date de fin',
		];
	}
}
