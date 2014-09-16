<?php

	/***********************
	 * configure 
	 **************************/
	require __DIR__ . '/env/env.php';

	env()->db0 = new \env\db\mysql_pdo("127.0.0.1", 3306, "db_weme_sdk", "db_weme_sdkdb_weme_sdk", "db_weme_sdk");
	//env()->db0 = new \env\db\mysqli("127.0.0.1", 3306);
	//env()->db0 = new \env\db\csvdb("/data/csv");

	//env()->cache0 = new \env\hash\memcached("127.0.0.1", 3307);
	//env()->cache0 = new \env\hash\redis("127.0.0.1", 3307);
	//env()->cache0 = new \env\hash\shm(0x11111, 0666);

	//env()->stack0 = new \env\stack\redis("127.0.0.1", 3307, "message");
	//env()->stack0 = new \env\stack\shm(0x11111, 0666);
	//env()->stack0 = new \env\stack\file("/tmp/stackfile");

	//env()->queue0 = new \env\queue\redis("127.0.0.1", 3307, "message");
	//env()->queue0 = new \env\queue\sockpipe("/tmp/pipe");
	//env()->queue0 = new \env\queue\file("/tmp/pipefile");


	/**************************
	 * explain uri 
	 * ***********************/
	env()->router = new \env\router\router();
	env()->stdin = new \env\hash\get();
	env()->stderr = new \env\stream\echo_output("json");
	//env()->cookie = new \env\hash\redis("127.0.0.1", 6380);
	//env()->cookie = new \env\hash\cookie();
	//env()->cookie = new \env\hash\post();
	env()->session = new \env\hash\session();

	env()->router->explain($_SERVER['REQUEST_URI'], $module_path, $output);
	
	switch ($output) {
	case 'json':
		env()->stdout = new \env\stream\echo_output("json");
		break;

	case 'html':
	case 'php':
	default:
		env()->stdout = new \env\stream\smarty($module_path.'.tpl');
		break;
	}


	/****************************
	 * call modules
	 * *************************/
	env()->caller = new \env\caller\obj();

	try {
		/* 
		 *  env()->call()   equals   env()->caller->call()
		 */
		$output = env()->call($module_path, env()->stdin->all());
		env()->stdout->write($output);

	} catch (Exception $e){
		env()->stderr->write($e->getMessage());
	}

