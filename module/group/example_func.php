<?php
function group_example_func($params) {

		env()->db0->start_transaction();

		//$query = env()->db0->query('select * from admin_user');
		$userid = env()->db0->get_value('select id from admin_user where username=?', array('andy'));
		//$userid = env()->db0->get_value('insert into admin_user (`username`, `cp_id`, `password`) value (?,?,?)', array('andy', 1, md5("123456")));

		env()->db0->commit();

		env()->session->set("userid", $userid);
		env()->cookie->set("a_value", $params['a']);
		env()->cookie->expired("a_value", 3);

		return array("a"=>$params["a"], 'userid'=>$userid);
}
