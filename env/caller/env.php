<?php

	namespace env\caller;

	class env implements \env\caller\icaller {

		private $env ;

		public function __construct(array $src){
			$this->env = new \Env;
			$this->env->load($src);
		}

		/* call a module
		 *
		 * @param	module_path, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($module_path, array $params = array()){
			return $this->env->call($module_path, $params);
		}
	}

