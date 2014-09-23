<?php
namespace group;
class session {
	public function run($params){
		env()->cookie->set("a_value", 100);
		env()->session->set('a_value', 1000);
		
		//header("Location: /module/session/redir.json");
		//header("Location: /module/group/redir.json");

		return array( 
			'cookie'=>env()->cookie->get('a_value'),
			'session'=>env()->session->get('a_value')
	   	);
	}
}
