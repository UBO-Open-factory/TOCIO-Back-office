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
            [['username', 'email', 'password'], 'required'],
            [['accessToken'], 'string'],
            [['lastAccess'], 'safe'],
            [['username'], 'string', 'max' => 20],
            [['email', 'authKey'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
		return [ 
				'id' => 'ID',
				'username' => 'Username',
				'email' => 'Email',
				'password' => 'Password',
				'authKey' => 'Auth Key',
				'accessToken' => 'Access Token',
				'lastAccess' => 'Last Access'
		];
	}

	// _____________________________________________________________________________________________
	/**
	 * Get the group name the Utilisateur belown to.
	 *
	 * @return string
	 */
	public function getAuthAssignment() {
		// SI L'UTILISATEUR EST DANS UN GROUPE
		return $this->hasOne( AuthAssignment::className(), [ 
				'user_id' => 'id'
		] );
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
