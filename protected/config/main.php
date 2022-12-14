<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'My Web Application',
	'defaultController' => 'site',
	// preloading 'log' component
	'preload' => array('log'),

	// autoloading model and component classes
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.modules.rights.*', //add this
		'application.modules.rights.components.*', //add this
	),

	'modules' => array(
		// uncomment the following to enable the Gii tool
		'gii' => array(
			'class' => 'system.gii.GiiModule',
			'password' => '1234',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters' => array('127.0.0.1', '::1'),
		),

		'rights' => array( //add this
			'install' => false, //add this
		), //add this
	),

	// application components
	'components' => array(

		'user' => array(
			'class' => 'RWebUser', //add this
			// enable cookie-based authentication
			'allowAutoLogin' => true,
			'loginUrl' => array('user/login')
		),

		'authManager' => array( //add this
			'class' => 'RDbAuthManager', //add this
			'assignmentTable'=>'authassignment', 
			'itemTable' => 'authitem',
			'itemChildTable' => 'authitemchild',
		),

		// uncomment the following to enable URLs in path-format
		'urlManager' => array(
			'urlFormat' => 'path',
			'showScriptName' => false,
			'rules' => array(
				'search/<action:\w+>/' => 'dashboard/search/<action>',
				'view/<action:\w+>/' => 'dashboard/view/<action>',
			),
		),

		// database settings are configured in database.php
		'db' => require(dirname(__FILE__) . '/database.php'),

		'errorHandler' => array(
			// use 'site/error' action to display errors
			'errorAction' => YII_DEBUG ? null : 'site/error',
		),

		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => array(
		// this is used in contact page
		'adminEmail' => 'webmaster@example.com',
	),
);
