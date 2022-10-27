<?php

class m221026_090312_create_board_members_table extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('board_members', array(
            'id' => 'pk',
            'user_id' =>'INTEGER NOT NULL',
            'board_id' =>'INTEGER NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		// $this->createIndex(
        //     'idx-board_members-user_id',
        //     'board_members',
        //     'user_id'
        // );

		// $this->addForeignKey(
        //     'fk-board_members-board_id',
        //     'board_members',
        //     'board_id',
        //     'boards',
        //     'id',
        //     'CASCADE'
        // );

		// $this->createIndex(
        //     'idx-board_members-board_id',
        //     'board_members',
        //     'board_id'
        // );

		// $this->addForeignKey(
        //     'fk-board_members-user_id',
        //     'board_members',
        //     'user_id',
        //     'users',
        //     'id',
        //     'CASCADE'
        // );
	}

	public function safeDown()
	{
		$this->dropForeignKey(
            'fk-board_members-board_id',
            'board_members'
        );

		$this->dropForeignKey(
            'fk-board_members-user_id',
            'board_members'
        );

		$this->dropIndex(
            'idx-board_members-user_id',
            'board_members'
        );
		$this->dropIndex(
            'idx-board_members-board_id',
            'board_members'
        );

		$this->dropTable('board_members');
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