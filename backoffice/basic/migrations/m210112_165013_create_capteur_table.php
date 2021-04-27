<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%capteur2}}`.
 */
class m210112_165013_create_capteur_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%capteur2}}', [
            'id' => $this->primaryKey(4),
            'nom' => $this->text()->notNull(),
        ]);
        $this->createIndex('id', '{{%capteur2}}', 'id');
        $this->alterColumn('{{%capteur2}}', 'id', $this->integer(4).' NOT NULL AUTO_INCREMENT');
        
        $this->createTable('{{%rel_modulecapteur2}}', [
            'idModule' => $this->text(50),
            'idCapteur' => $this->integer(4),
            'noncapteur' => $this->text(),
            'ordre' => $this->integer(2),
            'x' => $this->integer(3),
            'y' => $this->integer(3),
            'z' => $this->integer(3),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%capteur2}}');
        $this->dropTable('{{%rel_modulecapteur2}}');
    }
}
