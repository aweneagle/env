<?php
namespace app\log {
	class error {

		/* log error message
		 *
		 * @param	$params['reqid'], request id
		 * @param	$params['error'], Exception object
		 */
		public function run($params){
			$num = 3;
			$strace = $params['exp']->getTrace();
			$request_id = $params['reqid'];
			for ($i = 0 ; $i < $num; $i ++) {
				$stack = array_shift($strace);
				$line = array();
				array_unshift($line, $stack['args']); 
				array_unshift($line, $stack['function']."()"); 
				array_unshift($line, $stack['file'].'['.$stack['line'].']'); 
				array_unshift($line, $request_id); 
				array_unshift($line, date("Y-m-d H:i:s", APP_NOW_TIME)); 
				array_unshift($line, "[ERROR]"); 
				env()->log->write($line);
			}
		}
	}
}
