<?php

	namespace env\caller;

	class obj implements \env\caller\icaller {

		/* call a module
		 *
		 * @param	module_path, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($module_path, array $params = array()){
			$class = $module_path = "\\module".str_replace("/", "\\", $module_path);
			if (!class_exists($class)) {
				throw new \Exception("object::call($module_path,".json_encode($params).")");
			}
			$object = new $class;
			if (!method_exists($object, "run")) {
				throw new \Exception("object::call($module_path,".json_encode($params).")");
			}
			return $object->run($params);
		}
	}

