<?php
	namespace env\stream;
	class echo_output implements \env\stream\istream {
		public function write($data){
			if (!is_string($data) && $data != null) {
				throw new \Exception("Wrong param for echo_output::write(), need string");
			}
			echo $data;
		}
	}
