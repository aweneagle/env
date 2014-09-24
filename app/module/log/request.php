<?php
namespace app\log {
	class request {

		/* log request 
		 *
		 * @param	array $params, variables in order:
		 *				[ $requiest_id, 
		 */
		public function run($params){
			array_unshift($params, date("Y-m-d H:i:s", APP_NOW_TIME)); 
			array_unshift($params, "[REQUEST]"); 

			//env()->monitor->call("/write", array('data'=>$params, 'format'=>'json', 'mod'=>'/write'));
			env()->log->write($params);
		}
	}
}
