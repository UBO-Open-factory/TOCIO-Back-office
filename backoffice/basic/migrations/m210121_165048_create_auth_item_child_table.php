<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_item_child}}`.
 */
class m210121_165048_create_auth_item_child_table extends Migration {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		
// 		$this->createTable( '{{%auth_item_child2}}', [
// 				'parent' => $this->string( 64 )->notNull(),
// 				'parent' => $this->primaryKey(),
// 				'child' => $this->string( 64 )->notNull()->unique() ] );
		
// 		$this->createIndex( 'idx_parent', '{{%auth_item_child2}}', ['parent', 'child'] );
		
		
		// ****************************************************
		// Voir le scipt de création dans vendor/yiisoft/yii2/rbac/migrations
		// ****************************************************
		
		$this->createTable( '{{%auth_item_child2}}', [
				'parent' => $this->string( 64 )->notNull(),
				'child' => $this->string( 64 )->notNull(),
				'PRIMARY KEY ([[parent]], [[child]])',
				'FOREIGN KEY ([[parent]]) REFERENCES {{%auth_item2}} ([[name]])'.$this->buildFkClause( 'ON DELETE CASCADE', 'ON UPDATE CASCADE' ),
				'FOREIGN KEY ([[child]]) REFERENCES {{%auth_item2}} ([[name]])'.$this->buildFkClause( 'ON DELETE CASCADE', 'ON UPDATE CASCADE' ) ], $tableOptions);
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable( '{{%auth_item_child2}}' );
	}
}
