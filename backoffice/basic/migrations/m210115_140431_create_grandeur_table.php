<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%grandeur2}}`.
 */
class m210115_140431_create_grandeur_table extends Migration {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable( '{{%grandeur2}}', [
				'id' => $this->primaryKey(),
				'nature' => $this->string( 50 )->notnull(),
				'formatCapteur' => $this->string( 10 )->notnull(),
				'tablename' => $this->string( 50 ),
				'type' => $this->string( 14 )->notnull() ] );

		$this->createIndex( 'id', '{{%grandeur2}}', 'id' );
		$this->createIndex( 'nature', '{{%grandeur2}}', 'nature' );

		$this->execute( "ALTER TABLE {{%grandeur2}} CHANGE `nature` `nature` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'nature en toutes lettres (Unité)'" );
		$this->execute( "ALTER TABLE {{%grandeur2}} CHANGE `formatCapteur` `formatCapteur` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'signe - nombreDeCaracteresAvantVirgule, nombreDeCaracteresApresVirgule'" );
		$this->execute( "ALTER TABLE {{%grandeur2}} CHANGE `tablename` `tablename` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'Nom interne de la table'" );
		$this->execute( "ALTER TABLE {{%grandeur2}} CHANGE `type` `type` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  'Type des valeurs dans la table des mesures (Float, int, text, etc...)'" );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable( '{{%grandeur2}}' );
	}
}
