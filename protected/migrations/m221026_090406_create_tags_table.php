<?php

class m221026_090406_create_tags_table extends CDbMigration
{
	// public function up()
	// {
	// }

	// public function down()
	// {
	// 	echo "m221026_090406_create_tags_table does not support migration down.\n";
	// 	return false;
	// }

	
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->createTable('tags', array(
            'id' => 'pk',
			'name' => "string NOT NULL",
			'color_id' => "INTEGER NOT NULL",
			'board_id' => "INTEGER NOT NULL",
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		// $this->createIndex(
        //     'idx-tags-color_id',
        //     'tags',
        //     'color_id'
        // );

		$this->addForeignKey(
            'fk-tags-color_id',
            'tags',
            'color_id',
            'colors',
            'id',
            'CASCADE'
        );

		// $this->createIndex(
        //     'idx-tags-board_id',
        //     'tags',
        //     'board_id'
        // );

		$this->addForeignKey(
            'fk-tags-board_id',
            'tags',
            'board_id',
            'boards',
            'id',
            'CASCADE'
        );
	}

	public function safeDown()
	{
		$this->dropForeignKey(
            'fk-tags-color_id',
            'tags'
        );

		// $this->dropIndex(
        //     'idx-tags-color_id',
        //     'tags'
        // );

		$this->dropForeignKey(
            'fk-tags-board_id',
            'tags'
        );

		// $this->dropIndex(
        //     'idx-tags-board_id',
        //     'tags'
        // );

		$this->dropTable('tags');
	}
	
}