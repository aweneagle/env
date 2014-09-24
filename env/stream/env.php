<?php
	namespace env\stream;
	class env implements \env\stream\istream {
		private $env ;
		private $mod_path ;
		public function __construct(\env\caller\env $env, $mod_path){
			$this->env = $env;
			$this->mod_path = $mod_path;
		}

		/* write in data 
		 *
		 * @param 	data, string or array or null, 'false' could not be pushed 
		 *
		 * @return 	always true 
		 */
		public function write($data){
			$this->env->call($this->mod_path, $data);
		}

	}

