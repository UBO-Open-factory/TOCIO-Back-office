<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "method".
 *
 * @property int $id
 * @property int $id_capteur
 * @property int $id_carte
 * @property string $nom_method
 * @property string $method_include
 * @property string $method_statement
 * @property string $method_setup
 * @property string $method_read
 *
 * @property Capteur $capteur
 * @property Cartes $carte
 */
class Method extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_capteur', 'id_carte', 'nom_method'], 'required'],
            [['id_capteur', 'id_carte'], 'integer'],
            [['nom_method', 'method_include', 'method_statement', 'method_setup', 'method_read'], 'string'],
            [['id_capteur', 'id_carte'], 'unique', 'targetAttribute' => ['id_capteur', 'id_carte']],
            [['id_capteur'], 'exist', 'skipOnError' => true, 'targetClass' => Capteur::className(), 'targetAttribute' => ['id_capteur' => 'id']],
            [['id_carte'], 'exist', 'skipOnError' => true, 'targetClass' => Cartes::className(), 'targetAttribute' => ['id_carte' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_capteur' => 'Id Capteur',
            'id_carte' => 'Id Carte',
            'nom_method' => 'Nom Method',
            'method_include' => 'Method Include',
            'method_statement' => 'Method Statement',
            'method_setup' => 'Method Setup',
            'method_read' => 'Method Read',
        ];
    }

    /**
     * Gets query for [[Capteur]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCapteur()
    {
        return $this->hasOne(Capteur::className(), ['id' => 'id_capteur']);
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
}
