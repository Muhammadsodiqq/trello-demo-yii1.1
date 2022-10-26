<?php

class m221026_090326_create_columns_table extends CDbMigration
{
	public function safeUp()
	{
		$this->createTable('columns', array(
            'id' => 'pk',
			'title' => "string NOT NULL",
            'board_id' =>'INTEGER NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		$this->createIndex(
            'idx-columns-board_id',
            'columns',
            'board_id'
        );

		$this->addForeignKey(
            'fk-columns-board_id',
            'columns',
            'board_id',
            'boards',
            'id',
            'CASCADE'
        );
	}

	public function safeDown()
	{
		$this->dropForeignKey(
            'fk-columns-board_id',
            'columns'
        );

		$this->dropIndex(
            'idx-columns-board_id',
            'columns'
        );

		$this->dropTable('columns');
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