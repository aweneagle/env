<?php

	date_default_timezone_set('Asia/Hong_Kong');
	/***********************
	 * configuration 
	 **************************/
	$src = require(dirname(__FILE__). "/config.php");
	env_curr("APP_ENV");
	env()->load($src);

	/****************************
	 * call modules		, env()->call()	equals	env()->caller->call()
	 * *************************/
	env()->call('/entry');		// call app/entry.php
	env_destroy("APP_ENV");
