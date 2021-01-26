<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth_item2}}`.
 */
class m210121_161625_create_auth_item_table extends Migration {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
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
		$this->dropTable( '{{%auth_item2}}' );
	}
}
