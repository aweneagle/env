<?php
namespace app\example {
	class show_params {
		public function run($params){
			$session = env()->session->get(1);
			array_push($params, $session);
			return $params;
		}
	}
}
