<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tablemesure".
 *
 * @property int $id
 * @property string $nom Nom courant de la table
 * @property string $tablename Nom interne de la table
 * @property string $type
 */
class Tablemesure extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tablemesure';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
    	return [
    			[['nom'], 'required', "message" => "Le nom de la table doit être fait de la façon suivante 'Temperature (°C)'" ],
    			[['type'], 'string'],
    			[['nom'], 'string', 'max' => 25],
    			[['tablename'], 'string', 'max' => 50],
    			[['nom'], 'unique'],
    	];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
			'nom' => 'Nom courant de la table',
        	'type' => 'Type des valeurs',
			'tablename' => 'Nom interne de la table',
        ];
    }
}
