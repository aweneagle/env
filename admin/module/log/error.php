<?php
namespace adm\log {
	class error {

		/* log error message
		 *
		 * @param	$params['reqid'], request id
		 * @param	$params['error'], Exception object
		 */
		public function run($params){
			$num = 3;
			$line = array();
			$strace = $params['error']->getTrace();
			$request_id = $params['reqid'];
			for ($i = 0 ; $i < $num; $i ++) {
				$stack = array_shift($strace);
				array_unshift($line, $stack['args']); 
				array_unshift($line, $stack['function']); 
				array_unshift($line, $stack['file'].'['.$stack['line'].']'); 
				array_unshift($line, $request_id); 
				array_unshift($line, date("Y-m-d H:i:s", APP_NOW_TIME)); 
				array_unshift($line, "[ERROR]"); 
				env()->log->write($params);
			}
		}
	}
}
