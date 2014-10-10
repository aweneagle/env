<?php
	namespace env\stream;
	class stderr implements \env\stream\istream {

		/* write in data 
		 *
		 * @param 	string
		 *
		 * @return 	always true 
		 */
		public function write($data){
			$stderr = @fopen("php://stderr", "w");
			if (!is_string($data) && $data != null) {
				throw new \Exception ("Wrong param for stderr::write(), need string");
			}
			fwrite($stderr, $data);
		}

	}

