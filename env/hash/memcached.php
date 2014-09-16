<?php
	class memcached implements \env\ihash {
		private $conn = null; 

		public function __construct($host,$port){
			$this->conn = new Memcached();
			try {
				$this->conn->addServer($host, $port);
			} catch (\Exception $e) {
				throw new \Exception("redis::__construct($host,$port), err=".$e->getMessage());
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
		public function set($key, $value, $expired=0){
			if ($expired > 60*60*24*30) {
				$expired += time() ;
			}
			return $this->conn->set($key, $value, $expired);
		}

		/* key exists 
		 *
		 * @param	key, string
		 *
		 * @return true or false
		 */
		public function exists($key){
			if (!$val = $this->conn->get($key)) {
				return Memcached::RES_NOTFOUND == $this->conn->getResultCode();
			}
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
	}
