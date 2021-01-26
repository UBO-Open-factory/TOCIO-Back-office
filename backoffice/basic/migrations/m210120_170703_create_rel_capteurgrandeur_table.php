<?php
use yii\db\Migration;

/**
 * Handles the creation of table `{{%rel_capteurgrandeur2}}`.
 */
class m210120_170703_create_rel_capteurgrandeur_table extends Migration {
	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable( '{{%rel_capteurgrandeur2}}', [
				'idCapteur' => $this->integer( 4 )->notNull(),
				'idGrandeur' => $this->integer( 3 )->notNull() ] );

		$this->createIndex( 'cle', '{{%rel_capteurgrandeur2}}', [
				'idCapteur',
				'idGrandeur' ] );
		$this->createIndex( 'contgrandeur', '{{%rel_capteurgrandeur2}}', 'idGrandeur' );
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function safeDown() {
		$this->dropTable( '{{%rel_capteurgrandeur2}}' );
	}
}
