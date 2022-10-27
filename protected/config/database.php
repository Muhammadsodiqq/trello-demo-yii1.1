<?php

// This is the database connection configuration.
return array(
	// 'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/trello.db',
	// uncomment the following lines to use a MySQL database
	'connectionString' => 'pgsql:host=localhost;port=5432;dbname=trello2',
	'emulatePrepare' => true,
	'username' => 'postgres',
	'password' => '1234',
	'charset' => 'utf8',
);