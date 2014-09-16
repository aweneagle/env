<?php
	namespace env\hash;

	class session implements \env\hash\ihash {

		public function __construct(){
			session_start();
		}

		public function get($name){
			if (!isset($_SESSION[$name])) {
				return false;
			}
			return $_SESSION[$name];
		}


		public function set($name, $value){
			$_SESSION[$name] = $value;
		}


		public function expired($name, $seconds){
			throw new \Exception("session::expired($name, $seconds)");
		}

		public function exists($key){
			return isset($_SESSION[$key]);
		}

		public function delete($key){
			unset($_SESSION[$key]);
		}

		public function all(){
			return $_SESSION;
		}

	}
