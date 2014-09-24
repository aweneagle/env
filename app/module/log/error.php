<?php
namespace app\log {
	class error {

		/* log error message ( this mod could not be access by browser )
		 *
		 * @param	$params['reqid'], request id
		 * @param	$params['error'], Exception object
		 */
		public function run($params){
			$num = 2;
			$e = $params['exp'];
			$strace = $e->getTrace();
			$errmsg = $e->getMessage();
			$request_id = $params['reqid'];

			$line = array();
			array_unshift($line, $errmsg); 
			array_unshift($line, null); 
			array_unshift($line, $e->getFile().'['.$e->getLine().']'); 
			array_unshift($line, $request_id); 
			array_unshift($line, date("Y-m-d H:i:s", APP_NOW_TIME)); 
			array_unshift($line, "[ERROR]"); 
			env()->log->write($line);

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
