<?php
	namespace env\router;
	class router implements \env\router\irouter {

		private $default_output_format ;
		public function __construct($default_output_format = 'html') {
			$this->default_output_format = $default_output_format;
		}

		/* explain from `uri` into `module_path`, `output_format`
		 *
		 * @param	uri, string
		 * @param	module_path, string
		 * @param	output_format, string
		 *
		 * @return	always true	
		 */
		public function explain($uri, &$module_path, &$output_format){
			$uri = ltrim($uri, "/");
			$uri = substr($uri, strpos($uri, "/"));

			if (preg_match('/^([^?]+)\?.*$/', $uri, $match)) {
				$uri = $match[1];
			}
			if (preg_match('/^(.+)\.(\w+)$/', $uri, $match)) {
				$module_path = $match[1];
				$output_format = $match[2];

			} else {
				$module_path = $uri;
				$output_format = $this->default_output_format;
			}

			if ($module_path == null || $output_format == null) {
				throw \Exception("default::explain($uri)");
			}
			return true;
		}
	}

