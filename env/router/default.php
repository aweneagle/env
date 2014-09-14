<?php
	namespace \env\router;
	class default implements \env\router\irouter {

		/* explain from `uri` into `script_filename`, `output_format`
		 *
		 * @param	uri, string
		 * @param	script_filename, string
		 * @param	output_format, string
		 *
		 * @return	always true	
		 */
		public function explain($uri, &$script_filename, &$output_format){
			$script_filename = substr($uri, 0, strrpos($uri, '.') + 1);
			$output_format = substr($uri, strrpos($uri, '.') + 1);
			if ($script_filename == null || $output_format == null) {
				throw new Exception("default::explain($uri)");
			}
			return true;
		}
	}

