<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "module".
 *
 * @property int $id
 * @property string $idCapteur Plusieurs id de capteur sont possible, le séparateur est un point virgule (;)
 * @property string $identifiantReseau
 * @property string $description
 * @property int $idLocalisationModule Plusieurs id de localisation de module sont possible, le séparateur est un point virgule (;)
 * @property string $positionCapteur
 * @property int $actif
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'module';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idCapteur', 'identifiantReseau', 'description', 'idLocalisationModule', 'positionCapteur', 'actif'], 'required'],
            [['idCapteur', 'description', 'positionCapteur'], 'string'],
            [['idLocalisationModule', 'actif'], 'integer'],
            [['identifiantReseau'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identifiantReseau' => 'Identifiant Reseau',
            'description' => 'Description',
            'idCapteur' => 'Plusieurs id de capteur sont possible, le séparateur est un point virgule (;)',
            'idLocalisationModule' => 'Plusieurs id de localisation de module sont possible, le séparateur est un point virgule (;)',
            'positionCapteur' => 'Position Capteur',
            'actif' => 'Actif',
        ];
    }
}
