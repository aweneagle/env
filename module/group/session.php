<?php
namespace module\group;
class session {
	public function run($params){

		$session = env()->session->get("userid");
		$cookie = env()->cookie->get("a_value");

		return array('session'=>$session, 'cookie'=>$cookie);
	}
}
