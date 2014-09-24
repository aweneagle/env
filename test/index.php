<?php

	date_default_timezone_set('Asia/Hong_Kong');
	define("WEB_ROOT", realpath(dirname(dirname(__FILE__))));
	require(WEB_ROOT . "/env/env.php");

	/***********************
	 * configuration 
	 **************************/
	env("APP_ENV")->load(require(WEB_ROOT."/app/config.php"));
	foreach (scandir(dirname(__FILE__)) as $file) {
		$inputs = env()->call('/test/curl', $file);
		env()->call($inputs['mod'], $inputs['params']);
	}

	env("APP_ENV")->destroy();
