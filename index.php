<?php

	/***********************
	 * configuration 
	 *
	 **************************/
	require __DIR__ . '/env/env.php';




	/***********************
	 * data operations 
	 *
	 * **********************/ 

	//env()->db0 = new \env\db\mysql_pdo("127.0.0.1", 3306, "db_weme_sdk", "db_weme_sdkdb_weme_sdk", "db_weme_sdk");
	env()->db0 = new \env\db\mysqli("127.0.0.1", 3306, "db_weme_sdk", "db_weme_sdkdb_weme_sdk", "db_weme_sdk");

	//env()->form = new \env\upload\http("www.awen.com");
	env()->form = new \env\upload\http("b.pic.wemepi.com");

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
	
	
	/* ************************
	 * data structs 
	 *
	 * ************************/
	env()->server = new \env\globals\server;

	env()->files['uploaded']->name  vs  $_FILES['uploaded']['name'];

	env()->files = new \env\globals\files;						vs		$_FILES = array();
	env()->files['uploaded'] = new \env\globals\up_file;		vs		$_FILES['upload'] = array();



	/**************************
	 * explain uri 
	 * ***********************/
	env()->router = new \env\router\router();

	switch (env()->server->REQUEST_METHOD) {
		case 'GET':
			env()->stdin = new \env\hash\get();
			break;

		case 'POST':
			env()->stdin = new \env\hash\post();
			break;

		default:
			env()->stdin = new \env\hash\console();
			break;
	}

	env()->stderr = new \env\stream\echo_output("json");
	//env()->cookie = new \env\hash\redis("127.0.0.1", 6380);
	env()->cookie = new \env\hash\cookie();
	//env()->cookie = new \env\hash\post();
	env()->session = new \env\hash\session();



	env()->router->explain(env()->server->REQUEST_URI, $module_path, $output);
	
	switch ($output) {
	case 'json':
		env()->stdout = new \env\stream\echo_output("json");
		break;

	case 'text':
		env()->stdout = new \env\stream\echo_output('text');
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
	env()->caller = new \env\caller\obj(__DIR__ . "/module/");
	//env()->caller = new \env\caller\func(__DIR__ . "/module/");
	//env()->caller = new \env\caller\script(__DIR__ . "/module/");

	try {
		/* 
		 *  env()->call()   equals   env()->caller->call()
		 */
		$output = env()->call($module_path, env()->stdin->all());
		env()->stdout->write($output);

	} catch (Exception $e){
		env()->stderr->write($e->getMessage());
	}

