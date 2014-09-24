<?php


if (!defined("ADM_CONF")) {

	define("ADM_CONF", realpath(__FILE__));
	define("ADM_ROOT", realpath(dirname(__FILE__)));
	define("ADM_NAMESPACE_ROOT", "adm");

	require ADM_ROOT . '/../env/env.php';

	define("ADM_NOW_TIME", time());

	/***********************
	 * data operations 
	 *
	 * **********************/ 
	function adm_conf() {
		$env = array();

		//$env['db0'] = new \env\db\mysqli("127.0.0.1", 3306, "db_weme_sdk", "db_weme_sdkdb_weme_sdk", "db_weme_sdk");

		$env['cookie'] = new \env\hash\cookie();

		$env['session'] = new \env\hash\session();

		$env['form'] = new \env\upload\http("b.pic.wemepi.com");

		$env['router'] = new \env\router\router();

		$env['server'] = new \env\globals\server();

		$env['files'] = new \env\globals\files();

		$env['caller'] = new \env\caller\obj(ADM_ROOT . "/module/", ADM_NAMESPACE_ROOT);

		$env['log'] = new \env\stream\logfile(ADM_ROOT . "/log/info." . date("Y-m-d", ADM_NOW_TIME) . '.log');
		//$env['log'] = new \env\stream\echo_output('json', "\n");

		return $env;
	}
}

return adm_conf();
