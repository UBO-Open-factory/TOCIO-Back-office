<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rel_positionCapteur".
 *
 * @property int $idCapteur
 * @property int $idPosition
 *
 * @property Position $idPosition0
 * @property Capteur $idCapteur0
 */
class RelPositionCapteur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rel_positionCapteur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idCapteur', 'idPosition'], 'required'],
            [['idCapteur', 'idPosition'], 'integer'],
            [['idCapteur', 'idPosition'], 'unique', 'targetAttribute' => ['idCapteur', 'idPosition']],
            [['idPosition'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['idPosition' => 'id']],
            [['idCapteur'], 'exist', 'skipOnError' => true, 'targetClass' => Capteur::className(), 'targetAttribute' => ['idCapteur' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idCapteur' => 'Id Capteur',
            'idPosition' => 'Id Position',
        ];
    }

    /**
     * Gets query for [[IdPosition0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdPosition0()
    {
        return $this->hasOne(Position::className(), ['id' => 'idPosition']);
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
}
