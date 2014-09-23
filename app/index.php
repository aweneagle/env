<?php

	require_once "../path.php";
	require WEB_ROOT . '/env/env.php';

	/***********************
	 * configuration 
	 *
	 **************************/
	env('APP_ENV', require( APP_ROOT . "/include.php"));



	/****************************
	 * call modules
	 * *************************/

	try {
		/* 
		 *  env()->call()   equals   env()->caller->call()
		 */
		$input = env()->call('/read');

		env()->call('/log/user_behavior', $input);

		$route = env()->call('/route');

		$data = env()->call($route['mod'], $input);


		env()->call('/write', array('data'=>$data, 'format'=>$route['output_format'], 'mod'=>$route['mod']));

		env()->call('/log/user_behavior', $input);


	} catch (Exception $e){

		env()->call('/log/error', array($e));
	}

