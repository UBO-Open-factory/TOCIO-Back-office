<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rel_cartesmethod".
 *
 * @property int $id_carte
 * @property int $id_method
 *
 * @property Cartes $carte
 * @property Method $method
 */
class Relcartesmethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rel_cartesmethod';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_carte', 'id_method'], 'required'],
            [['id_carte', 'id_method'], 'integer'],
            [['id_carte', 'id_method'], 'unique', 'targetAttribute' => ['id_carte', 'id_method']],
            [['id_carte'], 'exist', 'skipOnError' => true, 'targetClass' => Cartes::className(), 'targetAttribute' => ['id_carte' => 'id']],
            [['id_method'], 'exist', 'skipOnError' => true, 'targetClass' => Method::className(), 'targetAttribute' => ['id_method' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_carte' => 'Id Carte',
            'id_method' => 'Id Method',
        ];
    }

    /**
     * Gets query for [[Carte]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarte()
    {
        return $this->hasOne(Cartes::className(), ['id' => 'id_carte']);
    }

    /**
     * Gets query for [[Method]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMethod()
    {
        return $this->hasOne(Method::className(), ['id' => 'id_method']);
    }
}
