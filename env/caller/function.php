<?php
	namespace \env\caller;
	class func  implements \env\caller\icaller {

		/* call a module
		 *
		 * @param	script_filename, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($script_filename, array $params = array()){
			include_once $script_filename;
			$func_name = trim(str_replace("/", "_", substr(0, - strlen(".php"), $script_filename)), "_");
			if (!function_exists($func_name)) {
				throw new \Exception("func::call($script_filename,".json_encode($params).")");
			}
			return $func_name($params);
		}
	}

