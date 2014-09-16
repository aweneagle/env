<?php

	namespace env\caller;

	class obj implements \env\caller\icaller {

		private $module_root ;

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
			$class = str_replace("/", "\\", $module_path);
			if (!class_exists($class)) {
				$try_to_load_file = $this->module_root . "/" . $module_path . ".php";
				if (file_exists($try_to_load_file)) {
					include_once ($try_to_load_file);
				}
				if (!class_exists($class)) {
					throw new \Exception("object::call($module_path,".json_encode($params).")");
				}
			}
			$object = new $class;
			if (!method_exists($object, "run")) {
				throw new \Exception("object::call($module_path,".json_encode($params).")");
			}
			return $object->run($params);
		}
	}

