<?php
	namespace \env\caller\;


	/*
	 * object-like module caller
	 *
	 * the module class should implements a function name 'run', which may accept an array as an param , forexample:
	 *
	 * class \env\module\example {
	 *		public function run($params) {
	 *
	 *			// do something here 
	 *
	 *			return array($output);
	 *		}
	 * }
	 *
	 *
	 */

	class object implements \env\icaller {

		/* call a module
		 *
		 * @param	script_filename, string
		 * @param	params,	array
		 *
		 * @return	array
		 */
		public function call($script_filename, array $params = array()){

			/* check if $script_filename is end with '.php' */

			if (substr($script_filename, - strlen('.php')) !== '.php') {
				throw new Exception("object::call($script_filename,".json_encode($params).")");
			}

			/* *
			 * $script_filename :  $module_path . ".php" 
			 *
			 * */

			$module_path = substr(0, strlen($script_filename) - strlen('.php'));
			$class = str_replace("/", "\\", $module_path);

			if (!class_exists($class)) {
				throw new Exception("object::call($script_filename,".json_encode($params).")");
			}
			$object = new $class;
			if (!method_exists($object, 'run')) {
				throw new Exception("object::call($script_filename,".json_encode($params).")");
			}
			return $object->run($params);
		}

	}
