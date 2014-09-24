<?php
	namespace env\hash;
	class console implements ihash{

		private $inputs = array();

		public function __construct(){
			global $argv;
			if ($argv) {
				foreach ($argv as $i => $a) {
					if ($delimit_pos = strpos($a, '=')) {	/* '=' is not the first characters of $a */
						$key = substr($a, 0, $delimit_pos);
						$val = substr($a, $delimit_pos +1 );

					} else {
						$key = $i;
						$val = $a;

					}

					$this->input[$key] = $val;
				}
			}
		}

		/* get value 
		 * 
		 * @param key, string
		 *
		 * @return false, if key not exists;  mixed , if success
		 */
		public function get($key){
			if (isset($this->input[$key])) {
				return $this->input[$key];
			}
			return false;
		}


		/* set value 
		 *
		 * @param key, string
		 * @param value, mixed
		 *
		 * @return always true
		 */
		public function set($key, $val){
			$this->input[$key] = $val;
			global $argv;
			array_push($argv, "$key=$val");
			return true;
		}


		/* set expired time
		 *
		 * @param key, string
		 * @param seconds, int, expired time , 0 means never expired
		 *
		 * @return trun on success;  false when key not exists
		 */
		public function expired($key, $seconds){
			throw new Exception("console::expired($key, $seconds)");
		}


		/* key exists 
		 *
		 * @param	key, string
		 *
		 * @return true or false
		 */
		public function exists($key){
			return isset($this->input[$key]);
		}


		/* get all values 
		 *
		 */
		public function all(){
			return $this->input;
		}


		/* delete value by key 
		 *
		 */
		public function delete($key){

			unset($this->input[$key]);

			/**********************
			 * some bugs are hiding in it ....
			 * **********************/
			/*
			global $argv;
			foreach ($argv as $i => $val) {
				if (is_int($key) && $i == $key) {
					unset($argv[$i]);
					break;

				} else if (!is_int($key) && $i === substr($val, 0, strpos($val, '='))) {
					unset($argv[$i]);
					break;
				}

			}
			 */

			return true;
		}

	}

