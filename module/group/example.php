<?php
namespace module\group;
class example {
	public function run($params){
		$query = env()->db0->query('select * from admin_user');
		return array("a"=>$params["a"]);
	}
}
