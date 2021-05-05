<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_item_child}}`.
 */
class m210121_165048_create_auth_item_child_table extends Migration 
{
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() 
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		
// 		$this->createTable( '{{%auth_item_child}}', [
// 				'parent' => $this->string( 64 )->notNull(),
// 				'parent' => $this->primaryKey(),
// 				'child' => $this->string( 64 )->notNull()->unique() ] );
		
// 		$this->createIndex( 'idx_parent', '{{%auth_item_child}}', ['parent', 'child'] );
		
		
		// ****************************************************
		// Voir le scipt de création dans vendor/yiisoft/yii/rbac/migrations
		// ****************************************************		
		$this->createTable( '{{%auth_item_child}}', 
			[
				'parent' => $this->string( 64 )->notNull(),
				'child' => $this->string( 64 )->notNull(),
			]
			, $tableOptions
		);
		$this->addPrimaryKey( 'pk_auth_item_child', '{{%auth_item_child}}', [ 'parent' , 'child' ] );
		$this->execute( "ALTER TABLE {{%auth_item_child}} ADD CONSTRAINT `auth_item_child_child` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION;" );
		$this->execute( "ALTER TABLE {{%auth_item_child}} ADD CONSTRAINT `auth_item_child_parent` FOREIGN KEY (`parent`) REFERENCES `auth_assignment` (`item_name`) ON DELETE NO ACTION ON UPDATE NO ACTION;" );

		$this->insert( '{{%auth_item_child}}', ["parent" => "Admin","child" => "createCapteur",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Admin","child" => "createGrandeur",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Admin","child" => "createLocalisation",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Admin","child" => "createModule",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Admin","child" => "createUser",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Utilisateur","child" => "createCapteur",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Utilisateur","child" => "createGrandeur",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Utilisateur","child" => "createLocalisation",] );
		$this->insert( '{{%auth_item_child}}', ["parent" => "Utilisateur","child" => "createModule",] );

	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() 
	{
		$this->dropTable( '{{%auth_item_child}}' );
	}
}
