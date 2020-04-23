<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rel_capteurgrandeur".
 *
 * @property int $idCapteur
 * @property int $idGrandeur
 *
 * @property Grandeur $idGrandeur0
 */
class Relcapteurgrandeur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rel_capteurgrandeur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idCapteur', 'idGrandeur'], 'required'],
            [['idCapteur', 'idGrandeur'], 'integer'],
            [['idCapteur', 'idGrandeur'], 'unique', 'targetAttribute' => ['idCapteur', 'idGrandeur']],
            [['idGrandeur'], 'exist', 'skipOnError' => true, 'targetClass' => Grandeur::className(), 'targetAttribute' => ['idGrandeur' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idCapteur' => 'Id Capteur',
            'idGrandeur' => 'Id Grandeur',
        ];
    }

    /**
     * Gets query for [[IdGrandeur0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdGrandeur0()
    {
        return $this->hasOne(Grandeur::className(), ['id' => 'idGrandeur']);
    }
}
