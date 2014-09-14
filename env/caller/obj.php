<?php

	namespace \env\caller;

	class obj implements \env\caller\icaller {

		/* call a module
		 *
		 * @param	script_filename, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($script_filename, array $params = array()){
			$class = $module_path = str_replace("/", "\\",  substr($script_filename, 0,  - strlen(".php")));
			if (!class_exists($class)) {
				throw new Exception("object::call($script_filename,".json_encode($params).")");
			}
			$object = new $class;
			if (!method_exists($object, "run")) {
				throw new Exception("object::call($script_filename,".json_encode($params).")");
			}
			return $object->run($params);
		}
	}

