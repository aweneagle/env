<?php
	global $argv;
	global $argc;

	$query = env()->db0->query('select * from admin_user');

	return array('query'=>$query, 'argv'=>$argv, 'argc'=>$argc);
