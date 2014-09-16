<?php
	namespace \env\stack;
	class redis implements \env\stack\istack{
		private $conn = null;

		public function __construct($host, $port, $prefix, $pconn=false){
			$this->conn = new Redis();
			if (!$pconn && !$this->conn->connect($host, $port)
				||
			$pconn && !$this->conn->pconnect($host, $port)
			) {
				throw new \Exception("redis::__construct($host, $port, $prefix, $pconn)");
			}
		}

		/* push data 
		 *
		 * @param $data, string or array, 'false' could not be pushed 
		 *
		 * @return true , if stack is full, false is returned;
		 */
		public function push($data){
			$this->conn->lpush($data);
		}


		/* pop data 
		 *
		 * @return string or array,  if stack is empty , false is returned;
		 */
		public function pop(){
			$this->conn->lpop($data);
		}
	}

