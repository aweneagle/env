<?php
	class Env {


		/* ==================================
		 * configure  io sources
		 * ==================================
		 */
		private $src = array();

		public function __construct(){
			$this->src['caller'] = new \env\caller\default;
		}

		public function __get($name){
			if (!isset($this->src[$name])) {
				throw new Exception("Env::__get(".$name.")");
			}
			return $this->src[$name];
		}

		public function __set($name, $value){
			$this->src[$name] = $value;
			return true;
		}

		public function __isset($name){
			return isset($this->src[$name]);
		}


		/* ================================ 
		 * init an environment 
		 * ================================
		 */

		/* global environment object list */
		private static $env_list = array();

		public function env_exists($env_name){
			return isset(self::$env_list[$env_name]);
		}

		public function env_ini($env_name){
			self::$env_list[$env_name] = new self;
			return true;
		}

		public function env_get($env_name){
			if (!self::env_exists($env_name)) {
				self::env_ini($env_name);
			}
			return self::$env_list[$env_name];
		}

		public function env_all_names(){
			return array_keys(self::$env_list);
		}


		/* ==================================
		 * call a module
                 * ==================================
                 */
		public function call($script_filename, array $params=array()) {
			$this->src['caller']->call($script_filename, $params);
		}
	}



	function env($env_name = null) {
		if ($env_name === null) {
			return Env::env_get('DEFAULT');
		}
		return Env::env_get($env_name);
	}
