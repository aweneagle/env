<?php
if (!defined("ENV_CONF")) {

	define("ENV_TIME_ZONE", "Asia/Hong_Kong");
	
	define("ENV_CONF", realpath(__FILE__));
	define("ENV_ROOT", dirname(__DIR__));

	date_default_timezone_set(ENV_TIME_ZONE);
	define("ENV_NOW_TIME", time());
	define("ENV_NOW_DATE", date("Y-m-d H:i:s", ENV_NOW_TIME));

	spl_autoload_register(function($class_name) {
		$file_path = ENV_ROOT . "/".str_replace("\\", "/", $class_name).".php";
		if (file_exists($file_path)) {
			require($file_path);
		}
	});

	set_exception_handler(function(Exception $e) {
		if ($env = env()) {
			$errmsg = $env->exception->format($e);
			foreach ($errmsg as $line) {
				$env->stderr->write($line);
			}
		}
	});

	set_error_handler(function($errno, $errmsg, $errfile, $errline, array $errcontext) {
		if ($env = env()) {
			$errmsg = $env->error->format($errno, $errmsg, $errfile, $errline, $errcontext);
			foreach ($errmsg as $line) {
				$env->stderr->write($line);
			}
		}
	}, E_ALL );

	class Env {


		/* ==================================
		 * configure  io sources
		 * ==================================
		 */
		private $src = array(
			'error'	=>	null,			
			'exception'	=>	null,
			'stderr'	=>	null
		);
		private $env_name ;

		private function __construct(){
			$this->src['error'] = new \env\error\error;
			$this->src['exception'] = new \env\exception\exception;
			$this->src['stderr'] = new \env\lib\io(new \env\stream\stderr, new \env\format\csv("|"));
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


		private static $curr_env = null;


		private static $env_history = array();


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
			self::$env_list[$env_name]->env_name = $env_name;
			return self::$env_list[$env_name];
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
			return $this;
		}


		public function halt($errmsg, $errno=null) {
			$this->sleep();
			throw new \Exception($errmsg, $errno);
		}

		public function sleep(){
			if ($this->env_name == self::$curr_env && !empty(self::$env_history)) {

				self::$curr_env = array_pop(self::$env_history);

				return true;
			}
			return false;
		}

		public function wakeup(){
			if ($this->env_name != self::$curr_env || self::$curr_env == null) {

				if (self::$curr_env) {
					array_push(self::$env_history, self::$curr_env);
				}
				self::$curr_env = $this->env_name;

				return true;
			}
			return false;
		}

		public function destroy() {
			foreach (self::$env_history as $i => $ename) {
				if ($this->env_name == $ename) {
					unset(self::$env_history[$i]);
				}
			}
			if (self::$curr_env == $this->env_name) {
				if (!empty(self::$env_history)) {
					self::$curr_env = array_pop(self::$env_history);
				} else {
					self::$curr_env = null;
				}
			}

			unset(self::$env_list[$this->env_name]);
			return true;
		}


		public function __destruct(){
			foreach ($this->src as $i => $src) {
				unset($this->src[$i]);
				unset($src);
			}
		}

		public function show_curr(){
			echo "===" . self::$curr_env . "===\n";
			print_r(self::$env_history);
			print_r(array_keys(self::$env_list));
		}
	}


	function env($env_name = null) {
		if ($env_name === null) {
			return Env::curr();
		} else {
			$env = Env::get($env_name);
			$env->wakeup();
			return $env;
		}
	}

	function env_get($env_name) {
		return Env::get($env_name);
	}

}
