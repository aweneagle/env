<?php
namespace module\group;
class example {
	public function run($params){
		$query = env()->db0->query('select * from admin_user');
		$userid = env()->db0->get_value('select id from admin_user where username=?', array('test'));

		env()->session->set("userid", $userid);
		env()->cookie->set("a_value", $params['a']);

		return array("a"=>$params["a"], 'userid'=>$userid);
	}
}
