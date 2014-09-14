<?php
	namespace \env\hash;

	class cookie implements \env\hash\ihash {


		public function get($name){
			if (!isset($_COOKIE[$name])) {
				return false;
			}
			return $_COOKIE[$name];
		}


		public function set($name, $value){
			if (setcookie($name, $value)) {
				return true;
			}
			throw new Exception("cookie::set($name,$value)");
		}

		public function exists($key){
			return isset($_COOKIE[$key]);
		}

		public function delete($key){
			unset($_COOKIE[$key]);
		}

	}
