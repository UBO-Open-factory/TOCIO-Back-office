<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%capteur}}`.
 */
class m210114_165013_create_capteur_table extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%rel_modulecapteur}}', 
        	[
            'idModule' => $this->string(50),
            'idCapteur' => $this->integer(4),
            'nomcapteur' => $this->text(),
            'ordre' => $this->integer(2),
            'x' => $this->integer(3),
            'y' => $this->integer(3),
            'z' => $this->integer(3)
        ] 
        , $tableOptions
        );
        $this->createIndex('contcapteur', '{{%rel_modulecapteur}}', 'idCapteur');
        
        $this->execute("ALTER TABLE {{%rel_modulecapteur}} ADD PRIMARY KEY (`idModule`(50),`idCapteur`,`nomcapteur`(50),`ordre`) USING BTREE");
        
        $this->execute("ALTER TABLE {{%rel_modulecapteur}} CHANGE `x` `x` INT(3) NOT NULL DEFAULT '0' COMMENT 'Coordonnées X'");
        $this->execute("ALTER TABLE {{%rel_modulecapteur}} CHANGE `y` `y` INT(3) NOT NULL DEFAULT '0' COMMENT 'Coordonnées Y'");
        $this->execute("ALTER TABLE {{%rel_modulecapteur}} CHANGE `z` `z` INT(3) NOT NULL DEFAULT '0' COMMENT 'Coordonnées Z'");
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rel_modulecapteur}}');
    }
}
