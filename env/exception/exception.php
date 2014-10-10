<?php
namespace env\exception;

/* catch exception,  and turn it into string */

class exception implements  \env\exception\iexception {
	private $lines = 3;


	/* @param	$e, Exception
	 *
	 * @return	array,  lines of exception message string 
	 */
	public function format(\Exception $e){
			$num = $this->lines;
			$strace = $e->getTrace();
			$errmsg = $e->getMessage();

			$return = array();
			$line = array();
			array_unshift($line, $errmsg); 
			$return[] =  array("[ERROR]", ENV_NOW_DATE, "error", $errmsg);
			$line = array();
			array_unshift($line, $e->getFile().'['.$e->getLine().']'); 
			array_unshift($line, "file"); 
			array_unshift($line, ENV_NOW_DATE); 
			array_unshift($line, "[ERROR]"); 
			$return[] =  $line;

			for ($i = 0 ; $i < $num; $i ++) {
				if ($stack = array_shift($strace) ) {
					$line = array();
					array_unshift($line, json_encode($stack['args'])); 
					array_unshift($line, $stack['function']."()"); 
					array_unshift($line, $stack['file'].'['.$stack['line'].']'); 
					array_unshift($line, "file"); 
					array_unshift($line, ENV_NOW_DATE); 
					array_unshift($line, "[ERROR]"); 
					$return[] =  $line;
				}
			}
			return $return;
	}
}
