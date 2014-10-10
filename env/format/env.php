<?php
	namespace env\format;

	class env implements iformat {
		private $env ;
		private $mod_path ;

		public function __construct(\env\caller\env $env, $mod_path){
			$this->env = $env;
			$this->mod_path = $mod_path;
		}

		/* write in data 
		 *
		 * @param 	data, param as array 
		 *
		 * @return 	always true 
		 */
		public function write(array $data){
			$this->env->call($this->mod_path, $data);
		}

		public function __destruct(){
			unset($this->env);
			unset($this->mod_path);
		}

	}

