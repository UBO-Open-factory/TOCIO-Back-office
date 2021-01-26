<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%utilisateur}}`.
 */
class m210115_153233_create_utilisateur_table extends Migration {

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
		// Table utilisateur -------------------------------------------------------------
		$this->createTable( '{{%utilisateur2}}', [
				'id' => $this->primaryKey()->unique(),
				'username' => $this->string( 20 )->notnull()->unique(),
				'email' => $this->string( 50 )->notnull(),
				'password' => $this->string( 255 )->notnull(),
				'authKey' => $this->string( 50 ),
				'accessToken' => $this->text(),
				'lastAccess' => $this->date()->notnull(),
				'lastAccess' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP' ] );

		$this->createIndex( 'id', '{{%utilisateur2}}', 'id' );

		$this->insert( '{{%utilisateur2}}', [
				"username" => "admin",
				"email" => "admin@server.fr",
				"password" => '$2y$13$E5nJTOVmgqXBxXs/PjlOluX0OrtnSzbX4XInE0KLGVLG.ivXC2bri' ] );

		// Table auth_assignement --------------------------------------------------------
		$this->createTable( '{{%auth_assignement2}}', [
				'item_name' => $this->primaryKey(),
				'item_name' => $this->string( 64 )->notNull(),
				'user_id' => $this->integer( 11 )->notNull(),
				'created_at' => $this->integer( 11 )->defaultValue( null ) ] );

		$this->addPrimaryKey( 'pk_primaryKey', '{{%auth_assignement2}}', [
				'item_name',
				'user_id' ] );
		$this->createIndex( 'user_id', '{{%auth_assignement2}}', 'user_id' );
		$this->insert( '{{%auth_assignement2}}', [
				"item_name" => "Admin",
				"user_id" => "1" ] );

		// Table auth_item --------------------------------------------------------------
		$this->createTable( '{{%auth_item2}}', [
				'name' => $this->primaryKey()->notNull()->unique(),
				'type' => $this->smallInteger( 6 )->notNull(),
				'description' => $this->text(),
				'rule_name' => $this->string( 64 ),
				'data' => $this->binary( 429496729 ), // 'Blob' space define in binary type (because blob is 4GB long)
				'created_at' => $this->integer( 11 ),
				'updated_at' => $this->integer( 11 ) ] );
		$this->alterColumn( '{{%auth_item2}}', 'name', 'varchar(64)' );
		$this->createIndex( 'idx-auth_item-type', '{{%auth_item2}}', 'type' );
		$this->createIndex( 'rule_name', '{{%auth_item2}}', 'rule_name' );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable( '{{%utilisateur2}}' );

		$this->delete( '{{%auth_assignement2}}', [
				'user_id' => 1 ] );
		$this->dropTable( '{{%auth_assignement2}}' );

		$this->dropTable( '{{%auth_item2}}' );
	}
}
