<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "capteur".
 *
 * @property int $id
 * @property string $nom
 * @property string $idGrandeur Valeurs sérialisée des id des grandeurs. Le séparateur est un point virgule (;)
 *
 * @property RelCapteurgrandeur[] $relCapteurgrandeurs
 * @property RelModulecapteur[] $relModulecapteurs
 */
class Capteur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'capteur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nom', 'idGrandeur'], 'required'],
            [['nom', 'idGrandeur'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nom' => 'Nom',
        	'idGrandeur' => 'Valeurs sérialisée des id des grandeurs. Le séparateur est un point virgule (;)',
        ];
    }

    /**
     * Gets query for [[RelCapteurgrandeurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelCapteurgrandeurs()
    {
        return $this->hasMany(RelCapteurgrandeur::className(), ['idCapteur' => 'id']);
    }

    /**
     * Gets query for [[RelModulecapteurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelModulecapteurs()
    {
        return $this->hasMany(RelModulecapteur::className(), ['idCapteur' => 'id']);
    }
}
