<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tm_luminositlux".
 *
 * @property int $id
 * @property string $timestamp
 * @property int $valeur
 * @property int $posX
 * @property int $posY
 * @property int $posZ
 * @property string $identifiantModule
 */
class TmLuminositlux extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tm_luminositlux';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['timestamp'], 'safe'],
            [['valeur', 'posX', 'posY', 'posZ', 'identifiantModule'], 'required'],
            [['valeur', 'posX', 'posY', 'posZ'], 'integer'],
            [['identifiantModule'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'timestamp' => 'Timestamp',
            'valeur' => 'Valeur',
            'posX' => 'Pos X',
            'posY' => 'Pos Y',
            'posZ' => 'Pos Z',
            'identifiantModule' => 'Identifiant Module',
        ];
    }
}
