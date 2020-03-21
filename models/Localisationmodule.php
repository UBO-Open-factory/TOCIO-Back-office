<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "localisationmodule".
 *
 * @property int $id
 * @property string $description
 * @property int $coordX Coordonnées x dans le système de repérage
 * @property int $coordY Coordonnées y dans le système de repérage
 * @property int $coordZ Coordonnées z dans le système de repérage
 */
class Localisationmodule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'localisationmodule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'coordX', 'coordY', 'coordZ'], 'required'],
            [['description'], 'string'],
            [['coordX', 'coordY', 'coordZ'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'coordX' => 'Coordonnées x dans le système de repérage',
            'coordY' => 'Coordonnées y dans le système de repérage',
            'coordZ' => 'Coordonnées z dans le système de repérage',
        ];
    }
}
