<?php
	namespace env\db;
	class mysql_pdo implements \env\db\idb {

		private $conn = null;
		private $in_transaction = false;

		public function __construct($host, $port, $user, $passwd, $dbname, $pconn=false){
			$this->conn = new \PDO("mysql:dbname=$dbname;host=$host;port=".intval($port).";", $user, $passwd);
		}

		/*	db query , optional params
		 *	
		 *	@param	sql, string; use "?" to identify params
		 *	@param	params,  array
		 *
		 *	@return array 
		 */
		public function query($sql, array $params=array()){
			if (!$st = $this->conn->prepare($sql)) {
				$this->exception("mysql_pdo::query($sql,".json_encode($params)."),errmsg=".$this->conn->getLastError().",errno=".$this->conn->getLastErrno());
			}
			if (!$st->execute($params)) {
				$this->exception("mysql_pdo::query($sql,".json_encode($params)."),errmsg=".$st->getLastError());
			}
			return $st->fetchAll(\PDO::FETCH_ASSOC);
		}

		private function exception($errmsg){
			if ($this->in_transaction) {
				$this->conn->rollBack();
				$this->in_transaction = false;
			}
			throw new \Exception($errmsg);
		}


		/*  db query,  optional params
		 *
		 *  @param	sql, string; use "?" to identify params
		 *  @param	params, array
		 *
		 *  @return string
		 */
		public function get_value($sql, array $params=array()){
			if ($result = $this->query($sql,$params)) {
				return array_shift($result);
			} else if ($result = $this->conn->lastInsertId()) {
				return $result;
			} else {
				return false;
			}
		}


		/* start transaction
		 *
		 * @return always true
		 */
		public function start_transaction(){
			$this->in_transaction = true;
			$this->conn->startTransaction();
		}


		/* commit transaction
		 *
		 * @return  always true
		 */
		public function commit(){
			$this->conn->commit();
			$this->in_transaction = false;
		}
	}
