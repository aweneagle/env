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
				$this->exception("mysql_pdo::query($sql,".json_encode($params)."),errmsg=".implode("|",$this->conn->errorInfo()).",errno=".$this->conn->errorCode());
			}
			if (!$st->execute($params)) {
				$this->exception("mysql_pdo::query($sql,".json_encode($params)."),errmsg=".implode("|", $st->errorInfo()).",errno=".$st->errorCode());
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
				return array_shift($result[0]);
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
			if (!$this->conn->beginTransaction()) {
				throw \Exception("mysql_pdo::start_transaction(".implode("|", $this->conn->errorInfo()).")");
			}
			return true;
		}


		/* commit transaction
		 *
		 * @return  always true
		 */
		public function commit(){
			if (!$this->conn->commit()) {
				throw \Exception("mysql_pdo::commit(".implode("|", $this->conn->errorInfo()).")");
			}
			$this->in_transaction = false;
			return true;
		}


		/* rollback transaction
		 *
		 * @return	always true
		 */
		public function rollback(){
			if (!$this->conn->rollBack()) {
				throw \Exception("mysql_pdo::commit(".implode("|", $this->conn->errorInfo()).")");
			}
			$this->in_transaction = false;
			return true;
		}
	}
