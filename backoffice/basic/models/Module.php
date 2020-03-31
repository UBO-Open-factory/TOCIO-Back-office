<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "module".
 *
 * @property string $identifiantReseau
 * @property string $nom Le nom du module
 * @property string $description
 * @property int $idLocalisationModule
 * @property int $actif 1 = Actif, 0 = Innactif
 *
 * @property Localisationmodule $idLocalisationModule0
 * @property RelModulecapteur[] $relModulecapteurs
 * @property Capteur[] $idCapteurs
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
            [['identifiantReseau', 'nom', 'description', 'idLocalisationModule', 'actif'], 'required'],
            [['description'], 'string'],
            [['idLocalisationModule', 'actif'], 'integer'],
            [['identifiantReseau'], 'string', 'max' => 10],
            [['nom'], 'string', 'max' => 50],
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
            'identifiantReseau' => 'Identifiant Reseau',
            'nom' => 'Nom',
            'description' => 'Description',
            'idLocalisationModule' => 'Id Localisation Module',
            'actif' => 'Actif',
        ];
    }

    /**
     * Gets query for [[IdLocalisationModule0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalisationModule()
    {
        return $this->hasOne(Localisationmodule::className(), ['id' => 'idLocalisationModule']);
    }

    /**
     * Gets query for [[RelModulecapteur]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelModulecapteur()
    {
        return $this->hasMany(RelModulecapteur::className(), ['idModule' => 'identifiantReseau']);
    }

    /**
     * Gets query for [[IdCapteurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCapteurs()
    {
        return $this->hasMany(Capteur::className(), ['id' => 'idCapteur'])->viaTable('rel_modulecapteur', ['idModule' => 'identifiantReseau']);
    }
}