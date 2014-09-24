<?php
	class test {
		public $name = null;
		public static $list = array();
		public function destroy(){
			unset(self::$list[$this->name]);
			echo "== destroy ==\n";
		}
		public function __destruct(){
			echo "== destruct == \n";
		}
	}

	function test($name){
		$t = new test;
		$t->name = $name;
		test::$list[$name] = $t;
		return $t;
	}

	test("a")->destroy();
	echo "hello\n";
