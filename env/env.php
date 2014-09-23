<?php
if (!define("ENV_CONF")) {

	define("ENV_CONF", realpath(__FILE__));
	define("ENV_ROOT", dirname(__DIR__));

	function __autoload($class_name) {
		$file_path = ENV_ROOT . "/".str_replace("\\", "/", $class_name).".php";
		if (file_exists($file_path)) {
			require($file_path);
		}
	}

	class Env {


		/* ==================================
		 * configure  io sources
		 * ==================================
		 */
		private $src = array();

		public function __construct(){
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

		private static $env_history = array();

		private static $curr_env = null;

		public static function set_curr($env_name){
			if (self::$curr_env) {
				array_push(self::$env_history, self::$curr_env);
			}
			self::$curr_env = $env_name;
			return self::get($env_name);
		}

		public static function restore(){
			if (empty(self::$env_history)) {
				self::$curr_env = null;
			} else {
				self::$curr_env = array_pop(self::$env_history);
			}
		}

		public static function curr(){
			if (!self::$curr_env) {
				self::$curr_env = 'DEFAULT';
			}
			return self::get(self::$curr_env);
		}

		public static function exists($env_name){
			return isset(self::$env_list[$env_name]);
		}

		public static function init($env_name){
			self::$env_list[$env_name] = new self;
			return true;
		}

		public static function get($env_name){
			if (!self::exists($env_name)) {
				self::init($env_name);
			}
			return self::$env_list[$env_name];
		}

		public static function all_names(){
			return array_keys(self::$env_list);
		}


		/* ==================================
		 * call a module
		 * ==================================
		 */
		public function call($script_filename, array $params=array()) {
			return $this->src['caller']->call($script_filename, $params);
		}


		/* 
		 * load configuration ,  if src already exists , it will be covered 
		 *
		 * @param	src, array( $src_name => $object )
		 *			example:
		 *				$env->load(array(
		 *					'db0'	=>	new	\env\idb\mysqli("127.0.0.1", 3306),
		 *					'cache0'=>	new \env\hash\memcache("127.0.0.1", 12008),
		 *					...
		 *					)
		 *				);
		 *
		 * @return	always true
		 *
		 */

		public function load(array $src){
			foreach ($src as $name => $obj) {
				$this->src[$name] = $obj;
			}
			return true;
		}


		public function __destruct(){
			foreach ($this->src as $i => $src) {
				unset($this->src[$i]);
				unset($src);
			}
		}
	}



	function env($env_name = null) {
		if ($env_name === null) {
			return Env::curr();
		} else {
			return Env::get($env_name);
		}
	}

	function env_curr($env_name){
		return Env::set_curr($env_name);
	}

	function env_restore(){
		Env::restore();
	}
}
