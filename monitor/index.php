<?php

	/***********************
	 * configuration 
	 **************************/
	$src = require(dirname(__FILE__). "/config.php");
	env_curr("APP_ENV");

	env()->load($src);


	env_curr("MONITOR_ENV");

	env()->load(require(APP_ROOT."/../monitor/config.php"));


	env_curr("ADMIN_ENV");

	env()->load(require(APP_ROOT . "/../admin/config.php"));



	/****************************
	 * call modules		, env()->call()	equals	env()->caller->call()
	 * *************************/
	env_curr("APP_ENV");
	env()->call('/entry');		// call app/entry.php

	env_curr("ADMIN_ENV");
	env()->call('/entry');		// call admin/entry.php


	env_destroy("APP_ENV");
	env_destroy("MONITOR_ENV");
	env_destroy("ADMIN_ENV");
