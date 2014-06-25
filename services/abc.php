<?php
class Abc {
	public function run($params){
//		header("Access-Control-Allow-Origin : https://www.google.ae");
//		header("Access-Control-Allow-Credentials : true");
//		header("Access-Control-Allow-Headers : MyHeader, MyHeader2");
//		header("Access-Control-Expose-Headers : YourHeader");
//		header("YourHeader : 1");
		return date("Y-m-d H:i:s", '1403353717') . "--------". $params['a'];
	}
}
