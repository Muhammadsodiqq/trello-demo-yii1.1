<?php

class m221026_090300_create_boards_table extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('boards', array(
            'id' => 'pk',
            'name' =>'string NOT NULL',
            'user_id' =>'INTEGER NOT NULL',
			'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		$this->createIndex(
            'idx-boards-user_id',
            'boards',
            'user_id'
        );

		$this->addForeignKey(
            'fk-boards-user_id',
            'boards',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );


	}

	public function safeDown()
	{
		$this->dropForeignKey(
            'fk-boards-user_id',
            'boards'
        );

		$this->dropIndex(
            'idx-boards-user_id',
            'boards'
        );

		$this->dropTable('boards');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}