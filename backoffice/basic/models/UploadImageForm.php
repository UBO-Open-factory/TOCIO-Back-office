<?php

namespace app\models;

use yii\base\Model;

class UploadImageForm extends Model {
	public $CSVFile;
	
	// Les éventuel message d'erreur lors de l'import
	public $ErrorMessages;
	
	// Le nom complet du fichier CSV (une fois uploadé) 
	public $fileName;
	
	
	public function rules() {
		return [[	['CSVFile'], 'file', 'skipOnEmpty' => false,
				]];
	}

	// _____________________________________________________________________________________________
	/**
	 * Ajoute un label lisible par un humain pour les différents champs du model UploadImageForm
	 * {@inheritDoc}
	 * @see \yii\base\Model::attributeLabels()
	 */
	public function attributeLabels(){
		return [
				'CSVFile' => 'Fichier journal au format CSV',
		];
	}
	
	
	// _____________________________________________________________________________________________
	/**
	 * Sauvegarde un fichier dans un répertoire local.
	 * @return boolean
	 */
	public function upload() {
		// Sauvegarde le fichier uploadé dans le répertoire local
		if( $this->validate() ) {
			
			// Construction du nom du fichier (il doit être unique)
			$timestamp = time();
			
			$this->fileName =  '../uploads/'.$timestamp."-".$this->CSVFile->baseName.'.'.$this->CSVFile->extension;
			$this->CSVFile->saveAs( $this->fileName );
			
			return true;
		} else {
			return false;
		}
	}
}
