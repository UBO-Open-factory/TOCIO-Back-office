<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "grandeur".
 *
 * @property int $id
 * @property string $nature nature en toutes lettres (Unité)
 * @property string $formatCapteur signe - nombreDeCaracteresAvantVirgule, nombreDeCaracteresApresVirgule
 * @property string|null $tablename Nom interne de la table
 * @property string $type Type des valeurs dans la table des mesures (Float, int, text, etc...)
 *
 * @property RelCapteurgrandeur[] $relCapteurgrandeurs
 * @property Capteur[] $idCapteurs
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
     * @see https://www.yiiframework.com/doc/guide/2.0/fr/input-validation
     */
    public function rules() {
        return [
            [['nature', 'formatCapteur', 'type'], 'required'],
            [['nature', 'tablename'], 'string', 'max' => 50],
            [['formatCapteur', 'type'], 'string', 'max' => 10],
        	[['nature'], 'trim'],	// Supprimer les espaces avant et après la saisie.
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nature' => 'Nature',
            'formatCapteur' => 'Format Capteur',
            'tablename' => 'Nom de la table de stockage des valeurs',
            'type' => 'Type des valeurs',
        ];
    }

    /**
     * Gets query for [[RelCapteurgrandeurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelCapteurgrandeurs()
    {
        return $this->hasMany(RelCapteurgrandeur::className(), ['idGrandeur' => 'id']);
    }

    /**
     * Gets query for [[IdCapteurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdCapteurs()
    {
    	return $this->hasMany(Capteur::className(), ['id' => 'idCapteur'])->viaTable('rel_capteurgrandeur', ['idGrandeur' => 'id']);
    }
}