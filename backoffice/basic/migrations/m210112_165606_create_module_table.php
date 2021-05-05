<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%module}}`.
 */
class m210112_165606_create_module_table extends Migration 
{
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() 
	{
		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		$this->createTable( '{{%capteur}}', 
			[
				'id' => $this->primaryKey( 4 ),
				'nom' => $this->text()->notNull() 
			] 
			, $tableOptions 
		);
		$this->createIndex( 'id', '{{%capteur}}', 'id' );
		$this->alterColumn( '{{%capteur}}', 'id', $this->integer( 4 ).' NOT NULL AUTO_INCREMENT' );

		$this->createTable( '{{%module}}', 
			[
				'identifiantReseau' => $this->string( 50 ),
				'nom' => $this->string( 50 )->notNull(),
				'description' => $this->text()->notNull(),
				'idLocalisationModule' => $this->integer( 3 )->notNull(),
				'actif' => $this->integer( 1 )->notNull() 
			] 
			, $tableOptions 
		);

		$this->createTable( '{{%localisationmodule}}', 
			[
				'id' => $this->primaryKey( 3 )->notnull()->unique(),
				'description' => $this->text(),
				'coordX' => $this->integer( 3 ),
				'coordY' => $this->integer( 3 ),
				'coordZ' => $this->integer( 3 ) 
			] 
			, $tableOptions 
		);
			$this->createIndex( 'id', '{{%localisationmodule}}', 'id' );
			$this->alterColumn( '{{%localisationmodule}}', 'id', $this->integer( 3 ).' NOT NULL AUTO_INCREMENT' );
			$this->execute( "ALTER TABLE {{%localisationmodule}} CHANGE `coordX` `coordX` INT(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées x dans le système de repérage'" );
			$this->execute( "ALTER TABLE {{%localisationmodule}} CHANGE `coordY` `coordY` INT(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées y dans le système de repérage'" );
			$this->execute( "ALTER TABLE {{%localisationmodule}} CHANGE `coordZ` `coordZ` INT(3) NOT NULL DEFAULT 0 COMMENT 'Coordonnées z dans le système de repérage'" );
			$this->execute( "ALTER TABLE {{%localisationmodule}} CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Description'" );

		$this->addPrimaryKey( 'pk_identifiantReseau', '{{%module}}', 'identifiantReseau' );
		$this->createIndex( 'identifiantReseau', '{{%module}}', 'identifiantReseau' );
		$this->createIndex( 'module_localisation', '{{%module}}', 'idLocalisationModule' );

		$this->execute( "ALTER TABLE {{%module}} ADD CONSTRAINT `module_localisation` FOREIGN KEY (`idLocalisationModule`) REFERENCES `localisationmodule` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;" );

		$this->execute( "ALTER TABLE {{%module}} CHANGE `nom` `nom` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Le nom du module'" );
		$this->execute( "ALTER TABLE {{%module}} CHANGE `actif` `actif` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '1 = Actif, 0 = Inactif'" );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() 
	{
		$this->dropTable( '{{%capteur}}' );
		$this->dropTable( '{{%module}}' );
		$this->dropTable( '{{%localisationmodule}}' );
	}
}
