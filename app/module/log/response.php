<?php
namespace module;
class response {
	public function run($params){
		array_unshift($params, date("Y-m-d H:i:s", APP_NOW_TIME)); 
		array_unshift($params, "[REQUEST]"); 
		env()->log->write($params);
	}
}
