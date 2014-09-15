<?php
namespace module\group;
class example {
	public function run($params){
		$query = env()->db0->query('select * from admin_user');
		$userid = env()->db0->query('select id from admin_user where userid=?', array('test'));
		return array("a"=>$params["a"], 'userid'=>$userid);
	}
}
