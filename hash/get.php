<?php
	namespace \env\hash;

	class get implements \env\ihash {


		public function get($name){
			if (!isset($_GET[$name])) {
				return false;
			}
			return $_GET[$name];
		}


		public function set($name, $value){
			$_GET[$name] = $value;
			return true;
		}


		public function exists($key){
			return isset($_GET[$key]);
		}


		public function delete($key){
			unset($_GET[$key]);
		}

	}
