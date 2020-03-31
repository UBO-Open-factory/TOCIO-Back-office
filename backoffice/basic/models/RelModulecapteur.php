<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rel_modulecapteur".
 *
 * @property string $idModule
 * @property int $idCapteur
 *
 * @property Capteur $idCapteur0
 * @property Module $idModule0
 */
class RelModulecapteur extends \yii\db\ActiveRecord
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
            [['idModule', 'idCapteur'], 'required'],
            [['idCapteur'], 'integer'],
            [['idModule'], 'string', 'max' => 10],
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
            'idCapteur' => 'Id Capteur',
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
