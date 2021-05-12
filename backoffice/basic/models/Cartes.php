<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cartes".
 *
 * @property int $id
 * @property string $nom
 *
 * @property RelCartesmethod[] $relCartesmethods
 * @property Method[] $methods
 */
class Cartes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cartes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nom'], 'required'],
            [['nom'], 'string'],
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
        ];
    }

    /**
     * Gets query for [[RelCartesmethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelCartesmethods()
    {
        return $this->hasMany(RelCartesmethod::className(), ['id_carte' => 'id']);
    }

    /**
     * Gets query for [[Methods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMethods()
    {
        return $this->hasMany(Method::className(), ['id' => 'id_method'])->viaTable('rel_cartesmethod', ['id_carte' => 'id']);
    }
}
