<?php
namespace env\error;

/* read php error ,and return string */
interface ierror {

	/* 
	 * @param errno, integer, level of the error raised 
	 * @param errcontext,  which is an array that points to the active symbol table at the point the error occurred. it can't be modified in format function
	 *
	 * @return  array, lines of error(or warning) string 
	 */
	public function format($errno, $errstr, $errfile, $errline, array $errcontext);
}
