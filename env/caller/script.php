<?php
	namespace env\caller;
	class script implements \env\caller\icaller {

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
			$script_filepath = $this->module_root ."/". $module_path . ".php";
			$GLOBALS['argv'] = $params;
			$GLOBALS['argc'] = count($params);
			return include_once($script_filepath);
		}
	}

