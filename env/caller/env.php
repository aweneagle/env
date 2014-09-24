<?php

	namespace env\caller;

	class env implements \env\caller\icaller {

		private $env ;
		private $conf;	/* configure file path */

		public function __construct($config_path){
			$this->conf = $config_path;
		}

		/* call a module
		 *
		 * @param	module_path, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($module_path, array $params = array()){
			if (!$this->env) {
				$this->env = env(spl_object_hash($this));
				$this->env->load(require($this->conf));
			}
			$this->env->wakeup();
			$data = $this->env->call($module_path, $params);
			$this->env->sleep();
		}

		public function __destruct(){
			unset($this->env);
		}
	}

