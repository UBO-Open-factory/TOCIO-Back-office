<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grandeur".
 *
 * @property int $id
 * @property string $nature nature en toutes lettres (Unité)
 * @property string $formatCapteur signe + nombreDeCaracteresAvantVirgule, nombreDeCaracteresApresVirgule
 * @property string|null $tablename Nom interne de la table
 * @property string $type Type des valeurs dans la table des mesures (Float, int, text, etc...)
 */
class Grandeur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grandeur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nature', 'formatCapteur', 'type'], 'required'],
            [['nature', 'tablename'], 'string', 'max' => 50],
            [['formatCapteur', 'type'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
    	return [
    			'id' => 'ID',
    			'nature' => 'Libellé',
    			'formatCapteur' => 'Formattage : [signe +] nombreDeCaracteresAvantVirgule , nombreDeCaracteresApresVirgule',
    			'tablename' => 'Nom interne de la table des mesures',
    			'type' => 'Type de la grandeur',
    	];
    }
}
