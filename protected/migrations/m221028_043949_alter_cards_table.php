<?php

class m221028_043949_alter_cards_table extends CDbMigration
{
	// public function up()
	// {
	// }

	// public function up()
	// {
	// 	echo "m221028_043949_alter_cards_table does not support migration down.\n";
	// 	return false;
	// }

	// Use safeUp/safeDown to do migration with transaction
	public function up()
	{
		$this->dropColumn('cards', 'user_id');
		$this->dropColumn('cards', 'deadline');
		$this->addColumn('cards', 'deadline', 'DATE');
		
	}

	public function down()
	{
	}
}