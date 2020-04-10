<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rel_modulecapteur".
 *
 * @property string $idModule
 * @property int $idCapteur
 * @property string $nomcapteur
 * @property int $ordre 
 * @property int $x Coordonnées X
 * @property int $y Coordonnées Y
 * @property int $z Coordonnées Z
 *
 * @property Capteur $idCapteur0
 * @property Module $idModule0
 */
class Relmodulecapteur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rel_modulecapteur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idModule', 'idCapteur', 'nomcapteur'], 'required'],
            [['idCapteur', 'ordre', 'x', 'y', 'z'], 'integer'],
            [['ordre', 'x', 'y', 'z'], 'default', 'value' => 0],	// Valeur par défault de l'ordre et des coordoonées.
            [['nomcapteur'], 'string'],
            [['idModule'], 'string', 'max' => 50],
            [['idModule', 'idCapteur'], 'unique', 'targetAttribute' => ['idModule', 'idCapteur']],
            [['idCapteur'], 'exist', 'skipOnError' => true, 'targetClass' => Capteur::className(), 'targetAttribute' => ['idCapteur' => 'id']],
            [['idModule'], 'exist', 'skipOnError' => true, 'targetClass' => Module::className(), 'targetAttribute' => ['idModule' => 'identifiantReseau']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
        		'idModule' => 'Id Module',
        		'idCapteur' => 'Capteur',
        		'nomcapteur' => 'Nom du capteur',
        		'ordre' => 'Ordre de cablage',
        		'x' => 'X',
        		'y' => 'Y',
        		'z' => 'Z',
        ];
    }

    /**
     * Gets query for [[IdCapteur0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCapteur0()
    {
        return $this->hasOne(Capteur::className(), ['id' => 'idCapteur']);
    }

    /**
     * Gets query for [[IdModule0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdModule0()
    {
        return $this->hasOne(Module::className(), ['identifiantReseau' => 'idModule']);
    }
}
