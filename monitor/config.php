<?php


if (!defined("MON_CONF")) {

	define("MON_CONF", realpath(__FILE__));
	define("MON_ROOT", realpath(dirname(__FILE__)));
	define("MON_NAMESPACE_ROOT", "mon");

	require MON_ROOT . '/../env/env.php';

	define("MON_NOW_TIME", time());

	/***********************
	 * data operations 
	 *
	 * **********************/ 
	function mon_conf() {
		$env = array();

		//$env['db0'] = new \env\db\mysqli("127.0.0.1", 3306, "db_weme_sdk", "db_weme_sdkdb_weme_sdk", "db_weme_sdk");

		$env['cookie'] = new \env\hash\cookie();

		$env['session'] = new \env\hash\session();

		$env['form'] = new \env\upload\http("b.pic.wemepi.com");

		$env['router'] = new \env\router\router();

		$env['server'] = new \env\globals\server();

		$env['files'] = new \env\globals\files();

		$env['caller'] = new \env\caller\obj(MON_ROOT . "/module/", MON_NAMESPACE_ROOT);
		//$env['caller'] = new \env\caller\func(MON_ROOT . "/module/", MON_NAMESPACE_ROOT);

		//$env['log'] = new \env\stream\logfile(MON_ROOT . "/log/info." . date("Y-m-d", MON_NOW_TIME) . '.log');
		$env['log'] = new \env\stream\echo_output('csv1', "\n");

		return $env;
	}
}

return mon_conf();
