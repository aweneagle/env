<?php

	namespace env\caller;

	class obj implements \env\caller\icaller {

		private $module_root ;
		private $namespace_root;

		public function __construct($module_root, $namespace_root = null){
			$this->module_root = $module_root;
			$this->namespace_root = $namespace_root;
		}

		/* call a module
		 *
		 * @param	module_path, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($module_path, array $params = array()){
			if ($this->namespace_root) {
				$class = "\\" . $this->namespace_root . str_replace("/", "\\", $module_path);
			} else {
				$class = str_replace("/", "\\", $module_path);
			}

			if (!class_exists($class)) {
				$try_to_load_file = $this->module_root . "/" . $module_path . ".php";
				if (file_exists($try_to_load_file)) {
					include_once ($try_to_load_file);
				}
				if (!class_exists($class)) {
					throw new \Exception("object::call($class,$module_path,".json_encode($params).")");
				}
			}
			$object = new $class;
			if (!method_exists($object, "run")) {
				throw new \Exception("object::call($module_path,".json_encode($params).")");
			}
			return $object->run($params);
		}
	}

