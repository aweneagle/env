<?php
	namespace env\caller;
	class func  implements \env\caller\icaller {
		private $module_root;

		public function __construct($module_root){
			$this->module_root = $module_root;
		}

		/* call a module
		 *
		 * @param	module_path, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($module_path, array $params = array()){
			include_once $this->module_root . "/" . $module_path . ".php";
			$func_name = trim(str_replace("/", "_", $module_path), "_");
			if (!function_exists($func_name)) {
				throw new \Exception("func::call($script_filename,".json_encode($params).")");
			}
			return $func_name($params);
		}
	}

