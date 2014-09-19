<?php
	namespace env\hash;

	class cookie implements \env\hash\ihash {

		private $path = '/';

		public function __construct($path = null){
			if ($path) {
				$this->path = $path;
			}
		}


		public function get($name){
			if (!isset($_COOKIE[$name])) {
				return false;
			}
			return $_COOKIE[$name];
		}


		public function set($name, $value){
			$_COOKIE[$name] = $value;
			if (setcookie($name, $value, 0, $this->path)) {
				return true;
			}
			throw new \Exception("cookie::set($name,$value)");
		}

		public function expired($name, $expired){
			if (!isset($_COOKIE[$name])) {
				return false;
			}
			$expired += time();
			$value = $_COOKIE[$name];
			if (setcookie($name, $value, $expired, $this->path)) {
				return true;
			}
			throw new \Exception("cookie::set($name,$value)");
		}

		public function exists($key){
			return isset($_COOKIE[$key]);
		}

		public function delete($key){
			unset($_COOKIE[$key]);
			if (setcookie($key, "", time()-3600, $this->path)) {
				return true;
			}
			throw new \Exception("cookie::delete($key)");
		}

		public function all(){
			return $_COOKIE;
		}

	}
