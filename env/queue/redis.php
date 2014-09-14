<?php
	namespce \env\queue;
	class redis implements \env\queue\iqueue {
		private $conn = null;

		public function __construct($host, $port, $prefix, $pconn=false){
			$this->conn = new Redis();
			if (!$pconn && !$this->conn->connect($host, $port)
				||
			$pconn && !$this->conn->pconnect($host, $port)
			) {
				throw new Exception("redis::__construct($host, $port, $prefix, $pconn)");
			}
		}

		/* push data 
		 *
		 * @param data, string or array, 'false' could not be pushed 
		 *
		 * @return true;  if queue is full, false is returned;
		 */
		public function push($data){
			$this->conn->lpush($data);
		}


		/* shift data 
		 *
		 * @return string or array;  if queue is empty, false is return
		 */
		public function shift(){
			return $this->conn->lshift();
		}
	}

