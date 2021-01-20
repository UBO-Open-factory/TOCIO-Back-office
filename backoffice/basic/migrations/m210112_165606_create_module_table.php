<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%module}}`.
 */
class m210112_165606_create_module_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%module2}}', [
            'identifiantReseau' => $this->string(50),
            'nom' => $this->string(50)->notNull(),
            'description' => $this->text()->notNull(),
            'idLocalisationModule' => $this->integer(3)->notNull(),
            'actif' => $this->integer(1)->notNull(),
        ]);
        $this->addPrimaryKey('pk_identifiantReseau', '{{%module2}}', 'identifiantReseau');
        $this->createIndex('identifiantReseau', '{{%module2}}', 'identifiantReseau');
        $this->createIndex('module_localisation', '{{%module2}}', 'idLocalisationModule');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%module2}}');
    }
}
