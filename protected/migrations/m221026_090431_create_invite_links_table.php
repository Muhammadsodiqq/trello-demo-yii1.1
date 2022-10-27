<?php

class m221026_090431_create_invite_links_table extends CDbMigration
{
	// public function up()
	// {
	// }

	// public function down()
	// {
	// 	echo "m221026_090431_create_invite_links_table does not support migration down.\n";
	// 	return false;
	// }

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->createTable('invite_links', array(
            'id' => 'pk',
			'token' => "string NOT NULL",
			'board_id' => "INTEGER NOT NULL",
			'expired_ad' => 'TIMESTAMP NOT NULL',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));

		// $this->createIndex(
        //     'idx-invite_links-board_id',
        //     'invite_links',
        //     'board_id'
        // );

		// $this->addForeignKey(
        //     'fk-invite_links-board_id',
        //     'invite_links',
        //     'board_id',
        //     'boards',
        //     'id',
        //     'CASCADE'
        // );
	}

	public function safeDown()
	{
		// $this->dropForeignKey(
        //     'fk-invite_links-board_id',
        //     'invite_links'
        // );

		// $this->dropIndex(
        //     'idx-invite_links-board_id',
        //     'invite_links'
        // );

		$this->dropTable('invite_links');
	}
}