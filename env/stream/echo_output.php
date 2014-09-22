<?php
	namespace env\stream;
	class echo_output implements \env\stream\istream {
		private $output_format;

		public function __construct($output_format){
			$class = "\\env\\stream\\echo_format_".$output_format;
			if (!class_exists($class)) {
				throw new \Exception("echo_format::__construct($output_format)");
			}
			$this->output_format = $output_format;	
		}

		/* write in data 
		 *
		 * @param 	data, string or array, 'false' could not be pushed 
		 *
		 * @return 	always true 
		 */
		public function write($data){
			if (!is_array($data) && !is_string($data) && $data != null && !is_numeric($data)) {
				throw new \Exception("echo_output::write($data)");
			}
			$class = "\\env\\stream\\echo_format_".$this->output_format;
			$obj = new $class;
			if (is_array($data)) {
				echo $obj->translate_array($data);
			} else {
				echo $obj->translate_str($data);
			}
		}

		/* read out data 
		 *
		 * @return 	string or array
		 */
		public function read(){
			return null;
		}
	}

	class echo_format_text {
		public function translate_str($data) {
			return $data;
		}

		public function translate_array($data) {
			return json_encode($data);
		}

	}

	class echo_format_json {
		public function translate_str($data) {
			return json_encode(array($data));
		}

		public function translate_array($data) {
			return json_encode($data);
		}
	}

	class echo_format_xml {
		public function translate_str($data) {
			return simple_xml(array($data));
		}
		public function translate_array($data) {
			return simple_xml($data);
		}
	}

	class echo_format_html {
		public function translate_str($data) {
			return "<html>" . $data . "</html>";
		}
		public function translate_array($data) {
			return "<html>" . json_encode($data) . "</html>";
		}
	}

