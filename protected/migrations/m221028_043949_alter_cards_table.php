<?php

class m221028_043949_alter_cards_table extends CDbMigration
{
	// public function up()
	// {
	// }

	// public function down()
	// {
	// 	echo "m221028_043949_alter_cards_table does not support migration down.\n";
	// 	return false;
	// }

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->dropColumn('cards', 'user_id');
	}

	public function safeDown()
	{
	}
}