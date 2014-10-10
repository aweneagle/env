<?php
namespace env\format;


/* turn an array into a string 
 *
 */

interface iformat {
	public function format(array $line);
}
