<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%localisationmodule2}}`.
 */
class m210120_164555_create_localisationmodule_table extends Migration {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable( '{{%localisationmodule2}}', [
				'id' => $this->primaryKey( 3 )->notnull()->unique(),
				'description' => $this->text(),
				'coordX' => $this->integer( 3 ),
				'coordY' => $this->integer( 3 ),
				'coordZ' => $this->integer( 3 ) ] );
		$this->createIndex( 'id', '{{%localisationmodule2}}', 'id' );
		$this->alterColumn( '{{%localisationmodule2}}', 'id', $this->integer( 3 ).' NOT NULL AUTO_INCREMENT' );

		$this->execute( "ALTER TABLE {{%localisationmodule2}} CHANGE `coordX` `coordX` INT(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées x dans le système de repérage'" );
		$this->execute( "ALTER TABLE {{%localisationmodule2}} CHANGE `coordY` `coordY` INT(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées y dans le système de repérage'" );
		$this->execute( "ALTER TABLE {{%localisationmodule2}} CHANGE `coordZ` `coordZ` INT(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées z dans le système de repérage'" );

		$this->execute( "ALTER TABLE {{%localisationmodule2}} CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Description'" );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable( '{{%localisationmodule2}}' );
	}
}
