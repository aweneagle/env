<?php
	namespace env\stream;
	interface istream {

		/* write in data 
		 *
		 * @param 	data, string or array or null, 'false' could not be pushed 
		 *
		 * @return 	always true 
		 */
		public function write($data);

	}

