<?php
	class redis implements \env\ihash {
		private $conn = null; 

		public function __construct($host,$port, $pconn=false){
			$this->conn = new Redis();
			try {
				if ($pconn) {
					$this->conn->pconnect($host, $port);
				} else {
					$this->conn->connect($host, $port);
				}
				$this->conn->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);
			} catch (Exception $e) {
				throw new Exception("redis::__construct($host,$port, $pconn), err=".$e->getMessage());
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
			return $this->conn->set($key, $value);
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
	}
