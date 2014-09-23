<?php

	/***********************
	 * data operations 
	 *
	 * **********************/ 
	$env = array();

	$env['db0'] = new \env\db\mysqli("127.0.0.1", 3306, "db_weme_sdk", "db_weme_sdkdb_weme_sdk", "db_weme_sdk");

	$env['stderr'] = new \env\stream\echo_output("json");

	$env['cookie'] = new \env\hash\cookie();
	
	$env['session'] = new \env\hash\session();

	$env['form'] = new \env\upload\http("b.pic.wemepi.com");

	$env['router'] = new \env\router\router();

	$env['server'] = new \env\globals\server();

	$env['files'] = new \env\globals\files();

	$env['caller'] = new \env\caller\obj(APP_ROOT . "/module/");

	$env['monitor'] = new \env\caller\env(MONITOR_ROOT . "/include.php");

	$env['manager'] = new \env\caller\env(MANAGER_ROOT . "/include.php");

	return $env;
