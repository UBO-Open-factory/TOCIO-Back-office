<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "module".
 *
 * @property string $nom Le nom du module
 * @property string $idCapteur Plusieurs id de capteur sont possible, le séparateur est un point virgule (;)
 * @property string $identifiantReseau
 * @property string $description
 * @property int $idLocalisationModule
 * @property string $positionCapteur
 * @property int $actif 1 = Actif, 0 = Innactif
 *
 * @property Localisationmodule $idLocalisationModule0
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
            [['nom', 'idCapteur', 'identifiantReseau', 'description', 'idLocalisationModule', 'positionCapteur', 'actif'], 'required'],
            [['idCapteur', 'description', 'positionCapteur'], 'string'],
            [['idLocalisationModule', 'actif'], 'integer'],
            [['nom'], 'string', 'max' => 50],
            [['identifiantReseau'], 'string', 'max' => 10],
            [['identifiantReseau'], 'unique'],
            [['idLocalisationModule'], 'exist', 'skipOnError' => true, 'targetClass' => Localisationmodule::className(), 'targetAttribute' => ['idLocalisationModule' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
    	return [
    			'identifiantReseau' => 'Identifiant réseau',
    			'description' => 'Description',
    			'idCapteur' => 'CapteursID. Le séparateur est un point virgule (;)',
    			'idLocalisationModule' => 'Localisation',
    			'positionCapteur' => "Position des capteurs. Position relative à la position du module. Sous la forme (0,0,0);(0,0,1)",
    			'nom' => 'Nom du module',
    			'actif' => 'Actif',
    	];
    }

    /**
     * Gets query for [[IdLocalisationModule0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdLocalisationModule0()
    {
        return $this->hasOne(Localisationmodule::className(), ['id' => 'idLocalisationModule']);
    }
}
