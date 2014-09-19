<?php
namespace group;
class example {
	public function run($params){

		env()->db0->start_transaction();

		//$query = env()->db0->query('select * from admin_user');
		//$userid = env()->db0->get_value('insert into admin_user (`username`, `cp_id`, `password`) values (?,?,?),(?,?,?)', array('andy', 1, md5("123456"), 'andy2', 1, md5("123456")));
		$userid = env()->db0->get_value('insert into admin_user (`username`, `cp_id`, `password`) values (?,?,?)', array('andy', 1, md5("123456")));
		$userid = env()->db0->get_value('insert into admin_user (`username`, `cp_id`, `password`) values (?,?,?)', array('andy2', 1, md5("123456")));
		//env()->db0->rollback();

		env()->db0->commit();
		return array("userid"=>$userid);

		env()->session->set("userid", $userid);


		env()->cookie->set("a_value", $params['a']);
		env()->cookie->expired("a_value", 30);
		$b = $params['b'];

		return array("a"=>$params["a"], 'b'=>$b, 'userid'=>$userid);
	}
}
