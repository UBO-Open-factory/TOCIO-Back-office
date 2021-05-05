<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%utilisateur}}`.
 */
class m210115_153233_create_utilisateur_table extends Migration 
{

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() 
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		// Table utilisateur -------------------------------------------------------------
		$this->createTable( '{{%utilisateur}}', 
			[
				'id' => $this->primaryKey()->unique(),
				'username' => $this->string( 20 )->notnull()->unique(),
				'email' => $this->string( 50 )->notnull(),
				'password' => $this->string( 255 )->notnull(),
				'authKey' => $this->string( 50 ),
				'accessToken' => $this->text(),
				'lastAccess' => $this->date()->notnull(),
				'lastAccess' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP' 
			] 
			, $tableOptions );

		$this->createIndex( 'id', '{{%utilisateur}}', 'id' );

		$this->insert( '{{%utilisateur}}', 
			[
				"username" => "admin",
				"email" => "admin@server.fr",
				"password" => '$2y$13$E5nJTOVmgqXBxXs/PjlOluX0OrtnSzbX4XInE0KLGVLG.ivXC2bri' 
			] 
		);

		// Table auth_assignment --------------------------------------------------------
		$this->createTable( '{{%auth_assignment}}', 
			[
				'item_name' => $this->primaryKey(),
				'item_name' => $this->string( 64 )->notNull(),
				'user_id' => $this->integer( 11 )->notNull(),
				'created_at' => $this->integer( 11 )->defaultValue( null ) 
			] 
			, $tableOptions );

		$this->addPrimaryKey( 'pk_primaryKey', '{{%auth_assignment}}', 
			[
				'item_name',
				'user_id' 
			] 
		);
		$this->createIndex( 'user_id', '{{%auth_assignment}}', 'user_id' );
		$this->insert( '{{%auth_assignment}}', 
			[
				"item_name" => "Admin",
				"user_id" => "1" 
			] 
		);
		$this->insert( '{{%auth_assignment}}', 
			[
				"item_name" => "Utilisateur",
				"user_id" => "5" 
			] 
		);

		// Table auth_item --------------------------------------------------------------
		$this->createTable( '{{%auth_item}}', 
			[
				'name' => $this->primaryKey()->notNull()->unique(),
				'type' => $this->smallInteger( 6 )->notNull(),
				'description' => $this->text(),
				'rule_name' => $this->string( 64 ),
				'data' => $this->binary( 429496729 ), // 'Blob' space define in binary type (because blob is 4GB long)
				'created_at' => $this->integer( 11 ),
				'updated_at' => $this->integer( 11 ) 
			] 
			, $tableOptions
		);
		$this->alterColumn( '{{%auth_item}}', 'name', 'varchar(64)' );
		$this->createIndex( 'idx-auth_item-type', '{{%auth_item}}', 'type' );
		$this->createIndex( 'rule_name', '{{%auth_item}}', 'rule_name' );

		$this->insert( '{{%auth_item}}', ["name" => "Admin","type" => 1,"description" => "administration du backoffice","rule_name" => NULL,"data" => NULL,"created_at" => NULL,"updated_at" => NULL,] );
		$this->insert( '{{%auth_item}}', ["name" => "createCapteur","type" => 2,"description" => "Créer un Capteur","rule_name" => NULL,"data" => NULL,"created_at" => NULL,"updated_at" => NULL,] );
		$this->insert( '{{%auth_item}}', ["name" => "createGrandeur","type" => 2,"description" => "Créer une Grandeur","rule_name" => NULL,"data" => NULL,"created_at" => NULL,"updated_at" => NULL,] );
		$this->insert( '{{%auth_item}}', ["name" => "createLocalisation","type" => 2,"description" => "Créer une Localisation","rule_name" => NULL,"data" => NULL,"created_at" => NULL,"updated_at" => NULL,] );
		$this->insert( '{{%auth_item}}', ["name" => "createModule","type" => 2,"description" => "Créer un Module","rule_name" => NULL,"data" => NULL,"created_at" => NULL,"updated_at" => NULL,] );
		$this->insert( '{{%auth_item}}', ["name" => "createUser","type" => 2,"description" => "Créer un Utilisateur","rule_name" => NULL,"data" => NULL,"created_at" => NULL,"updated_at" => NULL,] );
		$this->insert( '{{%auth_item}}', ["name" => "Utilisateur","type" => 1,"description" => "Simple utilisateur","rule_name" => NULL,"data" => NULL,"created_at" => NULL,"updated_at" => NULL,] );

	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() 
	{
		$this->dropTable( '{{%utilisateur}}' );

		$this->delete( '{{%auth_assignment}}', 
			[
				'user_id' => 1 
			] 
		);
		$this->dropTable( '{{%auth_assignment}}' );

		$this->dropTable( '{{%auth_item}}' );
	}
}
