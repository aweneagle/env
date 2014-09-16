<?php
	namespace \env\hash;

	class post implements \env\ihash {


		public function get($name){
			if (!isset($_POST[$name])) {
				return false;
			}
			return $_POST[$name];
		}


		public function set($name, $value, $expired=0){
			$_POST[$name] = $value;
			return true;
		}


		public function exists($key){
			return isset($_POST[$key]);
		}


		public function delete($key){
			unset($_POST[$key]);
		}

	}
