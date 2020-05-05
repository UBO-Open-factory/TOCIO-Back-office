<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "utilisateur".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property string $lastAccess
 * @property int $idGroupe
 *
 * @property UtilisateurGroup $idGroupe0
 */
class Utilisateur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'utilisateur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'idGroupe'], 'required'],
            [['accessToken'], 'string'],
            [['lastAccess'], 'safe'],
            [['idGroupe'], 'integer'],
            [['username'], 'string', 'max' => 20],
            [['email', 'authKey'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['idGroupe'], 'exist', 'skipOnError' => true, 'targetClass' => UtilisateurGroup::className(), 'targetAttribute' => ['idGroupe' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'lastAccess' => 'Last Access',
            'idGroupe' => 'Id Groupe',
        ];
    }

    /**
     * Gets query for [[IdGroupe0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdGroupe0()
    {
        return $this->hasOne(UtilisateurGroup::className(), ['id' => 'idGroupe']);
    }

    
	// _____________________________________________________________________________________________
	/**
	 * Validates password
	 *
	 * @param string $password
	 *        	password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password) {
// 		$inputPasswordHashed = \Yii::$app->getSecurity ()->generatePasswordHash ( $password );

		
		return  \Yii::$app->getSecurity()->validatePassword($password, $this->password);

	}
}
