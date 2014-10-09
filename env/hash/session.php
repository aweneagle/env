<?php
	namespace env\hash;

	class session implements \env\hash\ihash {
		private $prefix = '__SESSION_';

		public function __construct(){
			session_start();
		}

		public function get($name){
			if (is_numeric($name)) {
				$name = $this->prefix . $name;
			}
			if (!isset($_SESSION[$name])) {
				return false;
			}
			return $_SESSION[$name];
		}


		public function set($name, $value){
			if (is_numeric($name)) {
				$name = $this->prefix . $name;
			}
			$_SESSION[$name] = $value;
		}


		public function expired($name, $seconds){
			if (is_numeric($name)) {
				$name = $this->prefix . $name;
			}
			throw new \Exception("session::expired($name, $seconds)");
		}

		public function exists($name){
			if (is_numeric($name)) {
				$name = $this->prefix . $name;
			}
			return isset($_SESSION[$name]);
		}

		public function delete($name){
			unset($_SESSION[$name]);
		}

		public function all(){
			if (is_numeric($name)) {
				$name = $this->prefix . $name;
			}
			return $_SESSION;
		}

	}
