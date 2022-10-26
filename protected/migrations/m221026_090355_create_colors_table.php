<?php

class m221026_090355_create_colors_table extends CDbMigration
{
	// public function up()
	// {
	// }

	// public function down()
	// {
	// 	echo "m221026_090355_create_colors_table does not support migration down.\n";
	// 	return false;
	// }

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->createTable('colors', array(
            'id' => 'pk',
			'name' => "string NOT NULL",
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
	}

	public function safeDown()
	{
	}
}