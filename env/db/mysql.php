<?php
	class mysql_pdo implements \env\idb {

		private $conn = null;
		private $in_transaction = false;

		public function __construct($host, $port, $user, $password, $db) {
			try {
				$this->conn = new PDO("mysql:dbname=$db;host=$host;port=$port", $user, $password);
			} catch (PDOException $e) {
				throw new Exception("mysql_pdo::__construct($host, $port, $user, $password, $db),err=".$e->getMessage());
			}
		}


		/*	db query , optional params
		 *	
		 *	@param	sql, string; use "?" to identify params
		 *	@param	params, array 
		 *
		 *	@return array 
		 */
		public function query($sql, array $params=array()){

			try {
				if ( ! $pre = $this->conn->prepare($sql)) {
					throw new Exception ("mysql_pdo::query($sql,".json_encode($params).")");
				}
				if ( ! $pre->execute($params) ) {
					throw new Exception ("mysql_pdo::query($sql,".json_encode($params).")");
				}

				if ($result = $pre->fetchAll(PDO::FETCH_ASSOC)) {
					return $result;

				} else {
					return array();
				}

			} catch (Exception $e) {
				if ($this->in_transaction) {
					$this->conn->rollBack();
					$this->in_transaction = false;
				}
				throw $e;
			}

		}


		/*  db query,  optional params
		 *
		 *  @param	sql, string; use "?" to identify params
		 *  @param	params,  array
		 *
		 *  @return string
		 */
		public function get_value($sql, array $params=array()){
			if ($result = $this->query($sql, $params)) {
				return array_unshift($result);

			} else if ($last_insert_id = $this->conn->lastInsertId()) {
				return $last_insert_id;

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
			$this->conn->beginTransaction();
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
