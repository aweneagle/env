<?php
	namespace env\router;
	class router implements \env\router\irouter {

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
			$module_path = substr($uri, 0, strrpos($uri, '.'));
			$uri = substr($uri, 0, strpos($uri, '?'));
			$output_format = substr($uri, strrpos($uri, '.') + 1);
			if ($module_path == null || $output_format == null) {
				throw new Exception("default::explain($uri)");
			}
			return true;
		}
	}

