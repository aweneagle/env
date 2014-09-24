<?php
namespace mon\log {
	class response {
		public function run($params){
			array_unshift($params, date("Y-m-d H:i:s", APP_NOW_TIME)); 
			array_unshift($params, "[REQUEST]"); 
			//throw new \Exception("test");
			env()->halt("test");
			env()->log_out->write($params);
		}
	}
}
