<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%log2}}`.
 */
class m210120_171903_create_log_table extends Migration {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable( '{{%log2}}', [
				'id' => $this->primaryKey( 11 ),
				'level' => $this->string( 15 )->notNull(),
				'category' => $this->string( 20 )->notNull(),
				'log_time' => $this->integer( 11 )->notNull(),
				'prefix' => $this->string( 50 )->notNull(),
				'message' => $this->text()->notNull() ] );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable( '{{%log2}}' );
	}
}
