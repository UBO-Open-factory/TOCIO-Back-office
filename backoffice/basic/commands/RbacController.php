<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
	public function actionInit()
	{
		$auth = Yii::$app->authManager;
		// NETTOYAGE PREVENTIF ---------------------------------------------------------------------
		$auth->removeAll();
		
		
		
		// CREATIONS -------------------------------------------------------------------------------
		$createModule 		= $this->_CreatePerm($auth, ['name' => "createModule", 'description' => "Créer un Module"]);
		$createCapteur 		= $this->_CreatePerm($auth, ['name' => "createCapteur", 'description' => "Créer un Capteur"]);
		$createGrandeur 	= $this->_CreatePerm($auth, ['name' => "createGrandeur", 'description' => "Créer une Grandeur"]);
		$createLocalisation	= $this->_CreatePerm($auth, ['name' => "createLocalisation", 'description' => "Créer une Localisation"]);
		$createUser			= $this->_CreatePerm($auth, ['name' => "createUser", 'description' => "Créer un Utilisateur"]);

		
		
		// ROLE AUTHOR -----------------------------------------------------------------------------
		// ajoute un rôle  "author" et donne à ce rôle les permissions de creation
		$utilisateur = $auth->createRole('Utilisateur');
		$utilisateur->description 	= "Simple utilisateur";
		try { $auth->add($utilisateur); } catch(\Exception $e) {}
		$this->_addChild($auth, $utilisateur, $createModule);
		$this->_addChild($auth, $utilisateur, $createCapteur);
		$this->_addChild($auth, $utilisateur, $createGrandeur);
		$this->_addChild($auth, $utilisateur, $createLocalisation);

		
		
		// ROLE ADMIN ------------------------------------------------------------------------------
		// ajoute un rôle "admin" role et donne à ce rôle la permission "updateModule"
		// aussi bien que les permissions du rôle "author"
		$admin = $auth->createRole('Admin');
		$admin->description = "Administrateur du Back Office";
		try { $auth->add($admin); } catch(\Exception $e) {}
		$this->_addChild($auth, $admin, $createModule);
		$this->_addChild($auth, $admin, $createCapteur);
		$this->_addChild($auth, $admin, $createGrandeur);
		$this->_addChild($auth, $admin, $createLocalisation);
		$this->_addChild($auth, $admin, $createUser);
		
		// Assigne des rôles aux utilisateurs. 1 et 2 sont des identifiants retournés par IdentityInterface::getId()
		$auth->assign($admin, 1);
		$auth->assign($utilisateur, 2);
	}
	
	
	
	// _____________________________________________________________________________________________
	/**
	 *	Permet d'ajouter une permission à l'objet d'authentification passé en paramètre. 
	 * @param object $auth (Yii::$app->authManager)
	 * @param object $permission La permission préalablement déinif par _CreatePerm
	 */
	private function _addChild($auth, $user, $permission){
		try {
			$auth->addChild($user, $permission);
		} catch(\Exception $e) {
			echo "***** La permission ".$permission->name." existe déjà pour le rôle ".$user->name.".\n";
		}
	}
	
	// _____________________________________________________________________________________________
	/**
	 * Creation d'une permission
	 * @param object $auth (Yii::$app->authManager)
	 * @param array $param ['name', 'description']
	 * @return object permission
	 */
	private function _CreatePerm($auth, $param){
		$create	= $auth->createPermission($param['name']);
		$create->description = $param['description'];
		try {
			$auth->add($create);
			
		} catch(\Exception $e) {
			echo "***** La permission ".$param['name']." existe déjà.\n";
		}
		
		return $create;
	}
}