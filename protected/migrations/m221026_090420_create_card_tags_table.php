<?php

class m221026_090420_create_card_tags_table extends CDbMigration
{
	// public function up()
	// {
	// }

	// public function down()
	// {
	// 	echo "m221026_090420_create_card_tags_table does not support migration down.\n";
	// 	return false;
	// }

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->createTable('card_tags', array(
            'id' => 'pk',
			'card_id' => "INTEGER NOT NULL",
			'tag_id' => "INTEGER NOT NULL",
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		// $this->createIndex(
        //     'idx-card_tags-card_id',
        //     'card_tags',
        //     'card_id'
        // );

		// $this->addForeignKey(
        //     'fk-card_tags-card_id',
        //     'card_tags',
        //     'card_id',
        //     'cards',
        //     'id',
        //     'CASCADE'
        // );

		// $this->createIndex(
        //     'idx-card_tags-tag_id',
        //     'card_tags',
        //     'tag_id'
        // );

		// $this->addForeignKey(
        //     'fk-card_tags-tag_id',
        //     'card_tags',
        //     'tag_id',
        //     'tags',
        //     'id',
        //     'CASCADE'
        // );
	}

	public function safeDown()
	{
		// $this->dropForeignKey(
        //     'fk-card_tags-card_id',
        //     'card_tags'
        // );

		// $this->dropIndex(
        //     'idx-card_tags-card_id',
        //     'card_tags'
        // );

		// $this->dropForeignKey(
        //     'fk-card_tags-tag_id',
        //     'card_tags'
        // );

		// $this->dropIndex(
        //     'idx-card_tags-tag_id',
        //     'card_tags'
        // );

		$this->dropTable('card_tags');
	}
}