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

			if ( $rows = $st->fetchAll(\PDO::FETCH_ASSOC)) {
				/* SELECT some rows */
				return $rows;

			}

			/* SELECT,UPDATE,DELETE,INSERT error */
			if ($rows === false) {
				$this->exception("mysql_pdo::query($sql,".json_encode($params)."),errmsg=".implode("|", $st->errInfo()).",errno=".$st->errorCode());
			}

			/* DELETE, INSERT, UPDATE some rows */
			if ($rows_count = $st->rowCount()) {

				/* rowCount by INSERT, UPDATE, DELETE */
				if ($rows_count == 1 &&
					($insertId = $this->conn->lastInsertId())
				) {
					/* INSERT one row, return id */
					$rows_count = $insertId;

				}
				return array(array($rows_count));

			} else {

				/* SELECT empty row */
				/* DELETE, INSERT, UPDATE no row */
				return array();
			}
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
			if ($result = $this->get_row($sql,$params)) {
				return array_shift($result);
			} else {

				return false;
			}
		}


		/*  db query,  optional params
		 *
		 *  @param	sql, string; use "?" to identify params
		 *  @param	params, array
		 *
		 *  @return array
		 */
		public function get_row($sql, array $params=array()){
			if ($result = $this->query($sql,$params)) {
				return $result[0];
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
				throw new \Exception("mysql_pdo::start_transaction(".implode("|", $this->conn->errorInfo()).")");
			}
			return true;
		}


		/* commit transaction
		 *
		 * @return  always true
		 */
		public function commit(){
			if (!$this->conn->commit()) {
				throw new \Exception("mysql_pdo::commit(".implode("|", $this->conn->errorInfo()).")");
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
				throw new \Exception("mysql_pdo::commit(".implode("|", $this->conn->errorInfo()).")");
			}
			$this->in_transaction = false;
			return true;
		}
	}
