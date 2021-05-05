<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%grandeur}}`.
 */
class m210115_140431_create_grandeur_table extends Migration 
{
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() 
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable( '{{%grandeur}}', 
			[
				'id' => $this->primaryKey(),
				'nature' => $this->string( 50 )->notnull(),
				'formatCapteur' => $this->string( 10 )->notnull(),
				'tablename' => $this->string( 50 ),
				'type' => $this->string( 14 )->notnull() 
			] 
			, $tableOptions 
		);

		$this->createIndex( 'id', '{{%grandeur}}', 'id' );
		$this->createIndex( 'nature', '{{%grandeur}}', 'nature' );

		$this->execute( "ALTER TABLE {{%grandeur}} CHANGE `nature` `nature` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'nature en toutes lettres (Unité)'" );
		$this->execute( "ALTER TABLE {{%grandeur}} CHANGE `formatCapteur` `formatCapteur` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'signe - nombreDeCaracteresAvantVirgule, nombreDeCaracteresApresVirgule'" );
		$this->execute( "ALTER TABLE {{%grandeur}} CHANGE `tablename` `tablename` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'Nom interne de la table'" );
		$this->execute( "ALTER TABLE {{%grandeur}} CHANGE `type` `type` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'Type des valeurs dans la table des mesures (Float, int, text, etc...)'" );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() 
	{
		$this->dropTable( '{{%grandeur}}' );
	}
}
