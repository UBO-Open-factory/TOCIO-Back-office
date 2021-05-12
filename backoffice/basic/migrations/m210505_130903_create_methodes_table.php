<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%methodes}}`.
 */
class m210505_130903_create_methodes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable( '{{%method}}', 
            [
                'id' => $this->primaryKey( 4 ),
                'id_capteur' => $this->integer( 4 )->notNull() ,
                'nom_method' => $this->text()->notNull() ,
                'method_include' => $this->text()->notNull()->defaultValue('//Setup your include methode') ,
                'method_statement' => $this->text()->notNull()->defaultValue('//Setup your statement methode') ,
                'method_setup' => $this->text()->notNull()->defaultValue('//Setup your setup methode') ,
                'method_read' => $this->text()->notNull()->defaultValue('//Setup your reading methode')
            ] 
            , $tableOptions 
        );
            $this->alterColumn( '{{%method}}', 'id', $this->integer( 3 ).' NOT NULL AUTO_INCREMENT' );
            $this->execute( "ALTER TABLE {{%method}} ADD CONSTRAINT `method_id_carte` FOREIGN KEY (`id_capteur`) REFERENCES `capteur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;" );

        $this->createTable( '{{%cartes}}', 
            [
                'id' => $this->primaryKey( 4 ),
                'nom' => $this->text()->notNull() 
            ] 
            , $tableOptions 
        );
            $this->alterColumn( '{{%cartes}}', 'id', $this->integer( 3 ).' NOT NULL AUTO_INCREMENT' );

        $this->createTable( '{{%rel_cartesmethod}}', 
            [
                'id_carte' => $this->integer( 4 ),
                'id_method' => $this->integer( 4 ) 
            ] 
            , $tableOptions 
        );
            $this->addPrimaryKey( 'pk_rel_cartesmethod', '{{%rel_cartesmethod}}', [ 'id_carte' , 'id_method' ] );
            $this->execute( "ALTER TABLE {{%rel_cartesmethod}} ADD CONSTRAINT `rel_cartesmethod_id_cartes` FOREIGN KEY (`id_carte`) REFERENCES `cartes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;" );
            $this->execute( "ALTER TABLE {{%rel_cartesmethod}} ADD CONSTRAINT `rel_cartesmethod_id_method` FOREIGN KEY (`id_method`) REFERENCES `method` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;" );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable( '{{%method}}' );
        $this->dropTable( '{{%cartes}}' );
        $this->dropTable( '{{%rel_cartesmethod}}' );
    }
}
