<?php
namespace env\error;

/* read php error ,and return string */
class error implements \env\error\ierror {

	private $errnos = array(
		E_ERROR => "E_ERROR" , 
		E_WARNING => "E_WARNING" , 
		E_PARSE  => "E_PARSE" , 
		E_NOTICE => "E_NOTICE" , 
		E_CORE_ERROR => "E_CORE_ERROR" , 
		E_CORE_WARNING => "E_CORE_WARNING" , 
		E_COMPILE_ERROR => "E_COMPILE_ERROR" , 
		E_COMPILE_WARNING => "E_COMPILE_WARNING" , 
		E_USER_ERROR => "E_USER_ERROR" , 
		E_USER_WARNING => "E_USER_WARNING" , 
		E_USER_NOTICE => "E_USER_NOTICE" , 
		E_STRICT  => "E_STRICT" , 
		E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR" , 
		E_DEPRECATED => "E_DEPRECATED" , 
		E_USER_DEPRECATED => "E_USER_DEPRECATED" , 
		E_ALL  => "E_ALL" , 
	);

	/* 
	 * @param errno, integer, level of the error raised 
	 * @param errcontext,  which is an array that points to the active symbol table at the point the error occurred. it can't be modified in format function
	 *
	 * 
	 * @return  array, lines of error(or warning) string 
	 */
	public function format($errno, $errstr, $errfile, $errline, array $errcontext){
		if (!isset($this->errnos[$errno])) {
			$errno = 'UNKNOWN('.$errno.')';
		} else {
			$errno = $this->errnos[$errno];
		}

		$return = array();
		$return[] = array('['.$errno.']', ENV_NOW_DATE, 'error', $errstr);
		$return[] = array('['.$errno.']', ENV_NOW_DATE, 'file', $errfile."[$errline]");
		return $return;
	}
}
