<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
//     public $id;
//     public $username;
//     public $password;
//     public $authKey;
//     public $accessToken;

//     private static $users = [
//         '99' => [
//             'id' => '100',
//             'username' => 'Alex',
//             'password' => 'fablab',
//             'authKey' => 'test100key',
//             'accessToken' => '100-token',
//         ],
//         '100' => [
//             'id' => '100',
//             'username' => 'fablab',
//             'password' => 'fablab',
//             'authKey' => 'test100key',
//             'accessToken' => '100-token',
//         ],
//         '101' => [
//             'id' => '101',
//             'username' => 'demoFabLab',
//             'password' => 'demoFabLab',
//             'authKey' => 'test101key',
//             'accessToken' => '101-token',
//         ],
//     ];

	// _____________________________________________________________________________________________
	/**
	 * Renvoie true/false si l'utilisateur dont l'ID est passé en paramètre est un admin.
	 * @param integer $id an user id. 
	 * @return boolean
	 */
	public static function isAdmin($id){
		$l_OBJ_User = User::findOne($id);
		
		return ($l_OBJ_User->idGroupe == 1);
	}
    
    
    public static function tableName(){
    	return 'utilisateur';
    }
    
    /**
     * Trouve une identité à partir de l'identifiant donné.
     *
     * @param string|int $id l'identifiant à rechercher
     * @return IdentityInterface|null l'objet identité qui correspond à l'identifiant donné
     */
    public static function findIdentity($id) {
    	
    	return User::findOne($id);
//         return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//         foreach (self::$users as $user) {
//             if ($user['accessToken'] === $token) {
//                 return new static($user);
//             }
//         }
//         return null;
    	return User::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
//         foreach (self::$users as $user) {
//             if (strcasecmp($user['username'], $username) === 0) {
            	
//                 return new static($user);
//             }
//         }
//         return null;
    	return User::findOne(['username' 	=> $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    
    // _____________________________________________________________________________________________
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
//         return $this->password === $password;

    	return \Yii::$app->getSecurity()->validatePassword($password, $this->password); 
    }
}
