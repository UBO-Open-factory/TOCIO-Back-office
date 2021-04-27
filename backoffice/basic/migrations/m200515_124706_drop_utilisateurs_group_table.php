<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%utilisateurs_group}}`.
 */
class m200515_124706_drop_utilisateurs_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%utilisateurs_group}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%utilisateurs_group}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
