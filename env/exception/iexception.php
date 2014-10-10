<?php
namespace env\exception;

/* catch exception,  and turn it into string */
interface iexception {


	/* @param	$e, Exception
	 *
	 * @return	array,  lines of exception message string 
	 */
	public function format(\Exception $e);
}
