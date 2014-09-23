<?php


if (!defined("APP_CONF")) {

	define("APP_CONF", realpath(__FILE__));
	define("APP_ROOT", realpath(dirname(__FILE__)));

	require APP_ROOT . '/../env/env.php';

	define("APP_NOW_TIME", time());

	/***********************
	 * data operations 
	 *
	 * **********************/ 
	function app_conf() {
		$env = array();

		$env['db0'] = new \env\db\mysqli("127.0.0.1", 3306, "db_weme_sdk", "db_weme_sdkdb_weme_sdk", "db_weme_sdk");

		$env['cookie'] = new \env\hash\cookie();

		$env['session'] = new \env\hash\session();

		$env['form'] = new \env\upload\http("b.pic.wemepi.com");

		//$env['router'] = new \env\router\router();

		$env['server'] = new \env\globals\server();

		$env['files'] = new \env\globals\files();

		$env['caller'] = new \env\caller\obj(APP_ROOT . "/module/");

		$env['log'] = new \env\stream\file(APP_ROOT . "/log/info." . date("Y-m-d", APP_NOW_TIME) . '.log');
		//$env['log'] = new \env\stream\echo_output('json', "\n");

		return $env;
	}
}

return app_conf();
