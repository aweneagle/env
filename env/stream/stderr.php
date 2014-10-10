<?php
	namespace env\stream;
	class stderr implements \env\stream\istream {

		/* write in data 
		 *
		 * @param 	data, string or array or null, 'false' could not be pushed 
		 *
		 * @return 	always true 
		 */
		public function write($data){
			$stderr = @fopen("php://stderr", "w");
			if (is_array($data)) {
				fwrite($stderr, implode("\n", $data) . "\n");
			} else {
				fwrite($stderr, $data . "\n");
			}
		}

	}

