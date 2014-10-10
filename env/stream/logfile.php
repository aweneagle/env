<?php
	namespace env\stream;
	class logfile implements \env\stream\istream {
		private $filepath = null;
		public function __construct($filepath) {
			$this->filepath = $filepath;
		}

		/* write in data 
		 *
		 * @param 	data, param as string
		 *
		 * @return 	always true 
		 */
		public function write($data){
			if (!is_string($data) && $data != null) {
				throw new \Exception("Wrong param for logfile::write(), need string,data=".$data);
			}
			@file_put_contents($this->filepath, $data, FILE_APPEND);
		}

	}

