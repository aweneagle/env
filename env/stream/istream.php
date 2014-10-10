<?php
	namespace env\stream;
	interface istream {

		/* write in data 
		 *
		 * @param 	$data,  param as  string 
		 *
		 * @return 	always true 
		 */
		public function write($data);

	}

