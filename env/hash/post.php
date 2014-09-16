<?php
	namespace env\hash;

	class post implements \env\hash\ihash {


		public function get($name){
			if (!isset($_POST[$name])) {
				return false;
			}
			return $_POST[$name];
		}


		public function set($name, $value){
			$_POST[$name] = $value;
			return true;
		}


		public function expired($name, $seconds){
			throw new \Exception("post::expired($name, $seconds)");
		}


		public function exists($key){
			return isset($_POST[$key]);
		}


		public function delete($key){
			unset($_POST[$key]);
		}


		public function all(){
			return $_POST;
		}

	}
