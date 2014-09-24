<?php

	date_default_timezone_set('Asia/Hong_Kong');
	/***********************
	 * configuration 
	 **************************/
	$src = require(dirname(__FILE__). "/config.for-test.php");
	env("APP_ENV")->load($src);

	/****************************
	 * call modules		, env()->call()	equals	env()->caller->call()
	 * *************************/
	env()->call('/entry');		// call app/entry.php

	env("APP_ENV")->destroy();
