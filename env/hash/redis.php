<?php
	namespace env\hash;

	class redis implements \env\hash\ihash {
		private $conn = null; 

		public function __construct($host, $port, $pconn=false){
			$this->conn = new \Redis();
			try {
				if ($pconn) {
					$this->conn->pconnect($host, $port);
				} else {
					$this->conn->connect($host, $port);
				}
			} catch (\Exception $e) {
				throw new \Exception("redis::__construct($host,$port, $pconn), err=".$e->getMessage());
			}
		}

		/* get value 
		 * 
		 * @param key, string
		 *
		 * @return false, if key not exists;  mixed , if success
		 */
		public function get($key){
			$val = $this->conn->get($key);
			if ($val === false) {
				return false;
			}
			return $val;
		}

		/* set value 
		 *
		 * @param key, string
		 * @param value, mixed
		 *
		 * @return always true
		 */
		public function set($key, $value){
			if (!$this->conn->set($key, $value)) {
				throw new \Exception("redis::set(".$key.",".$value.")");
			}
			return true;
		}


		public function expired($key, $seconds){
			if (!$this->conn->setTimeout($key, $seconds)) {
				throw new \Exception("redis::expired(".$key.",".$seconds.")");
			}
			return true;
		}


		/* key exists 
		 *
		 * @param	key, string
		 *
		 * @return true or false
		 */
		public function exists($key){
			return $this->conn->exists($key);
		}

		/* delete key 
		 *
		 * @param	key, string
		 *
		 * @return  true or false
		 */
		public function delete($key){
			return $this->conn->delete($key);
		}


		/* get all 
		 *
		 * @return array
		 */
		public function all(){
			$all = array();
			$keys = $this->conn->keys("*");
			if ($keys) {
				foreach ($keys as $k) {
					$all[$k] = $this->conn->get($k);
				}
			}
			return $all;
		}
	}
