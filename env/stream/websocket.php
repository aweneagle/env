<?php
	interface istream {

		/* write in data 
		 *
		 * @param 	data, string or array, 'false' could not be pushed 
		 *
		 * @return 	always true 
		 */
		public function write($data);


		/* read out data 
		 *
		 * @return 	string or array
		 */
		public function read();
	}

