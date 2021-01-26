<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%module}}`.
 */
class m210112_165606_create_module_table extends Migration {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable( '{{%capteur2}}', [
				'id' => $this->primaryKey( 4 ),
				'nom' => $this->text()->notNull() ] );
		$this->createIndex( 'id', '{{%capteur2}}', 'id' );
		$this->alterColumn( '{{%capteur2}}', 'id', $this->integer( 4 ).' NOT NULL AUTO_INCREMENT' );

		$this->createTable( '{{%module2}}', [
				'identifiantReseau' => $this->string( 50 ),
				'nom' => $this->string( 50 )->notNull(),
				'description' => $this->text()->notNull(),
				'idLocalisationModule' => $this->integer( 3 )->notNull(),
				'actif' => $this->integer( 1 )->notNull() ] );

		$this->addPrimaryKey( 'pk_identifiantReseau', '{{%module2}}', 'identifiantReseau' );
		$this->createIndex( 'identifiantReseau', '{{%module2}}', 'identifiantReseau' );
		$this->createIndex( 'module_localisation', '{{%module2}}', 'idLocalisationModule' );

		$this->execute( "ALTER TABLE {{%module2}} ADD CONSTRAINT `module_localisation` FOREIGN KEY (`idLocalisationModule`) REFERENCES `localisationmodule2` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;" );

		$this->execute( "ALTER TABLE {{%module2}} CHANGE `nom` `nom` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Le nom du module'" );
		$this->execute( "ALTER TABLE {{%module2}} CHANGE `actif` `actif` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '1 = Actif, 0 = Inactif'" );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable( '{{%capteur2}}' );
		$this->dropTable( '{{%module2}}' );
	}
}
