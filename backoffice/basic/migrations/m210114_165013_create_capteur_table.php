<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%capteur2}}`.
 */
class m210114_165013_create_capteur_table extends Migration
{

    /**
     *
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rel_modulecapteur2}}', [
            'idModule' => $this->string(50),
            'idCapteur' => $this->integer(4),
            'nomcapteur' => $this->text(),
            'ordre' => $this->integer(2),
            'x' => $this->integer(3),
            'y' => $this->integer(3),
            'z' => $this->integer(3)
        ]);
        $this->createIndex('contcapteur', '{{%rel_modulecapteur2}}', 'idCapteur');
        
        $this->execute("ALTER TABLE {{%rel_modulecapteur2}} ADD PRIMARY KEY (`idModule`(50),`idCapteur`,`nomcapteur`(50),`ordre`) USING BTREE");
        
        $this->execute("ALTER TABLE {{%rel_modulecapteur2}} CHANGE `x` `x` INT(3) NOT NULL DEFAULT '0' COMMENT 'Coordonnées X'");
        $this->execute("ALTER TABLE {{%rel_modulecapteur2}} CHANGE `y` `y` INT(3) NOT NULL DEFAULT '0' COMMENT 'Coordonnées Y'");
        $this->execute("ALTER TABLE {{%rel_modulecapteur2}} CHANGE `z` `z` INT(3) NOT NULL DEFAULT '0' COMMENT 'Coordonnées Z'");
    }

    /**
     *
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rel_modulecapteur2}}');
    }
}
