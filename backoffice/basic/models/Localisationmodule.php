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
 *
 * @property Module[] $modules
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
            'description' => 'Localisation',
            'coordX' => 'Coord X',
            'coordY' => 'Coord Y',
            'coordZ' => 'Coord Z',
        ];
    }

    /**
     * Gets query for [[Modules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::className(), ['idLocalisationModule' => 'id']);
    }
}
