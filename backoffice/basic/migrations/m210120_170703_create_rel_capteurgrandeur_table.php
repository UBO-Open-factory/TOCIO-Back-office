<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%rel_capteurgrandeur}}`.
 */
class m210120_170703_create_rel_capteurgrandeur_table extends Migration 
{
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() 
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable( '{{%rel_capteurgrandeur}}', 
			[
				'idCapteur' => $this->integer( 4 )->notNull(),
				'idGrandeur' => $this->integer( 3 )->notNull() 
			] 
			, $tableOptions 
		);
		$this->addPrimaryKey( 'pk_rel_capteurgrandeur', '{{%rel_capteurgrandeur}}', [ 'idCapteur' , 'idGrandeur' ] );

		$this->createIndex( 'cle', '{{%rel_capteurgrandeur}}', 
			[
				'idCapteur',
				'idGrandeur' 
			] 
		);
		$this->createIndex( 'contgrandeur', '{{%rel_capteurgrandeur}}', 'idGrandeur' );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() 
	{
		$this->dropTable( '{{%rel_capteurgrandeur}}' );
	}
}
