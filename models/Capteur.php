<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "capteur".
 *
 * @property int $id
 * @property string $nom
 * @property string $idGrandeur Valeurs sérialisée des id des grandeurs. Le séparateur est un point virgule (;)
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
}
