<?php

class m221026_090343_create_card_members_table extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('card_members', array(
            'id' => 'pk',
            'card_id' =>'INTEGER NOT NULL',
            'user_id' =>'INTEGER NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		// $this->createIndex(
        //     'idx-card_members-user_id',
        //     'card_members',
        //     'user_id'
        // );

		// $this->addForeignKey(
        //     'fk-card_members-user_id',
        //     'card_members',
        //     'user_id',
        //     'users',
        //     'id',
        //     'CASCADE'
        // );

		// $this->createIndex(
        //     'idx-card_members-card_id',
        //     'card_members',
        //     'card_id'
        // );

		// $this->addForeignKey(
        //     'fk-card_members-card_id',
        //     'card_members',
        //     'card_id',
        //     'cards',
        //     'id',
        //     'CASCADE'
        // );
	}

	public function safeDown()
	{
		// $this->dropForeignKey(
        //     'fk-card_members-user_id',
        //     'card_members'
        // );

		// $this->dropIndex(
        //     'idx-card_members-user_id',
        //     'card_members'
        // );

		// $this->dropForeignKey(
        //     'fk-card_members-card_id',
        //     'card_members'
        // );

		// $this->dropIndex(
        //     'idx-card_members-card_id',
        //     'card_members'
        // );

		$this->dropTable('card_members');
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