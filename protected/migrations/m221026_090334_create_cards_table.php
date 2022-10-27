<?php

class m221026_090334_create_cards_table extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('cards', array(
            'id' => 'pk',
			'title' => "string NOT NULL",
			'description' => "TEXT NOT NULL",
			'deadline' =>  'TIMESTAMP',
            'column_id' =>'INTEGER NOT NULL',
            'user_id' =>'INTEGER NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		// $this->createIndex(
        //     'idx-cards-user_id',
        //     'cards',
        //     'user_id'
        // );

		// $this->addForeignKey(
        //     'fk-cards-user_id',
        //     'cards',
        //     'user_id',
        //     'users',
        //     'id',
        //     'CASCADE'
        // );

		// $this->createIndex(
        //     'idx-cards-column_id',
        //     'cards',
        //     'column_id'
        // );

		// $this->addForeignKey(
        //     'fk-cards-column_id',
        //     'cards',
        //     'column_id',
        //     'columns',
        //     'id',
        //     'CASCADE'
        // );
	}

	public function safeDown()
	{
		// $this->dropForeignKey(
        //     'fk-cards-user_id',
        //     'cards'
        // );

		// $this->dropIndex(
        //     'idx-cards-user_id',
        //     'cards'
        // );

		// $this->dropForeignKey(
        //     'fk-cards-column_id',
        //     'cards'
        // );

		// $this->dropIndex(
        //     'idx-cards-column_id',
        //     'cards'
        // );

		$this->dropTable('cards');
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