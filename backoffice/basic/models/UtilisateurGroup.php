<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "utilisateur_group".
 *
 * @property int $id
 * @property string $groupName
 *
 * @property Utilisateur[] $utilisateurs
 */
class UtilisateurGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utilisateur_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['groupName'], 'required'],
            [['groupName'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'groupName' => 'Group Name',
        ];
    }

    /**
     * Gets query for [[Utilisateurs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUtilisateurs()
    {
        return $this->hasMany(Utilisateur::className(), ['idGroupe' => 'id']);
    }
}
