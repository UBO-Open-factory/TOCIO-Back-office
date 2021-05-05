<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%log2}}`.
 */
class m210120_171903_create_log_table extends Migration 
{
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() 
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable( '{{%log}}', 
			[
				'id' => $this->primaryKey( 11 ),
				'level' => $this->string( 15 )->notNull(),
				'category' => $this->string( 20 )->notNull(),
				'log_time' => $this->integer( 11 )->notNull(),
				'prefix' => $this->string( 50 )->notNull(),
				'message' => $this->text()->notNull() 
			] 
			, $tableOptions 
		);
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() 
	{
		$this->dropTable( '{{%log}}' );
	}
}
