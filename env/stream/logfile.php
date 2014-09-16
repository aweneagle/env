<?php
	namespace \env\stream;
	class logfile implements \env\stream\istream {
		private $filepath = null;
		public function __construct($filepath) {
			$this->filepath = $filepath;
		}

		/* write in data 
		 *
		 * @param 	data, null or string or array
		 *
		 * @return 	always true 
		 */
		public function write($data){
			if (is_array($data)) {
				array_unshift($data, date("Y-m-d H:i:s", time()));
			} else if (is_string($data)) {
				$data = array(
					date("Y-m-d H:i:s", time()),
					$data);
			} else {
				throw new Exception("file_log::write($data)");
			}
			@file_put_contents($this->filepath, implode("|", $data), FILE_APPEND);
		}

	}

