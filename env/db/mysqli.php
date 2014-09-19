<?php
	namespace env\db;

	class mysqli implements \env\db\idb {

		private $conn ;
		private $in_transaction;

		public function __construct($host, $port, $user, $passwd, $dbname, $pconn=false){
			if ( ! $this->conn = mysqli_connect($host, $user, $passwd, $dbname, $port) ) {
				throw new \Exception("mysqli::__construct($host, $port, $user, $passwd, $dbname, $pconn),err=".mysqli_connect_error().",errno=".mysqli_connect_errno());
			}
			mysqli_autocommit($this->conn, true);
			$this->in_transaction = false;
		}

		/*	db query , optional params
		 *	
		 *	@param	sql, string; use "?" to identify params
		 *	@param	params,  array
		 *
		 *	@return array 
		 */
		public function query($sql, array $params=array()){

			if (!empty($params)) {

				/*		select * from table where field_name=?	 */
				if (($pos = strpos($sql, "?")) && substr($sql, $pos-1, 1) !== '\\') {
					$tmp_sql = '';
					foreach (explode("?", $sql) as $chip) {
						$tmp_sql .= $chip ;
						if (!empty($params)) {
							$tmp_sql .= '\''.preg_replace('/\'/', '\\\'', array_shift($params)).'\'';
						}
					}
					$sql = $tmp_sql;

				/*		select * from table where field_name=:name	*/
				} else {
					foreach ($params as $search => $replacement) {
						if (!preg_match('/^:[\w]+$/', $search)) {
							throw new \Exception ("mysqli::query(),err=".json_encode($params));
						}
						preg_match('/[\']/', $replacement, $match);
						$sql = str_replace($search, '\''.preg_replace('/\'/', '\\\'', $replacement).'\'', $sql);
					}
				}
			}

			if (!$st = mysqli_prepare($this->conn, $sql)) {
				throw new \Exception("mysqli::query($sql,".json_encode($params)."),error=".mysqli_error($this->conn).",errno=".mysqli_errno($this->conn));
			}

			if (!mysqli_stmt_execute($st)) {
				throw new \Exception("mysqli::query($sql,".json_encode($params)."),error=".mysqli_stmt_error($st).",errno=".mysqli_stmt_errno($st));
			}

			/* INSERT, UPDATE, DELETE some rows */
			if (!$result = mysqli_stmt_get_result($st)) {


				$affected_row = mysqli_stmt_affected_rows($st);

				/* INSERT one row */

				if ($affected_row === 1) {
					$insert_id = mysqli_stmt_insert_id($st);

					mysqli_stmt_free_result($st);

					return array(array($insert_id));

				} else if ($affected_row === -1) {
					throw new \Exception("mysqli::query($sql,".json_encode($params)."),error=".mysqli_stmt_error($st).",errno=".mysqli_stmt_errno($st));

				} else if ($affected_row === null) {
					throw new \Exception("mysqli::query($sql,".json_encode($params)."),error=".mysqli_stmt_error($st).",errno=".mysqli_stmt_errno($st));

				} else if ($affected_row === 0){

					/* INSERT, UPDATE, DELETE no row */
					mysqli_stmt_free_result($st);

					return array();

				} else {

					/* INSERT, UPDATE, DELETE some rows */
					mysqli_stmt_free_result($st);
					return array(array($affected_row));
				}


			/* SELECT some rows */
			} else {

				$data = array();
				for ( ; $row = mysqli_fetch_assoc($result); ) {
					$data[] = $row;
				}


				if ($data) {

					mysqli_stmt_free_result($st);
					mysqli_free_result($result);

					return $data;
				}

			}



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
		 *  @return string
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
			if (!mysqli_autocommit($this->conn, false)) {
				throw new \Exception("mysqli::start_transaction(),error=".mysqli_error($this->conn).",errno=".mysqli_errno($this->conn));
			}
			return true;

		}


		/* commit transaction
		 *
		 * @return  always true
		 */
		public function commit(){
			if (!mysqli_commit($this->conn)) {
				throw new \Exception("mysqli::commit(),error=".mysqli_error($this->conn).",errno=".mysqli_errno($this->conn));
			}
			if (!mysqli_autocommit($this->conn, true)) {
				throw new \Exception("mysqli::commit(),error=".mysqli_error($this->conn).",errno=".mysqli_errno($this->conn));
			}
			$this->in_transaction = true;
			return true;
		}



		/* roll back transaction 
		 *
		 * @return	always true
		 */
		public function rollback(){
			if (!mysqli_rollback($this->conn)){
				throw new \Exception("mysqli::rollback(),error=".mysqli_error($this->conn).",errno=".mysqli_errno($this->conn));
			}
			if (!mysqli_autocommit($this->conn, true)){
				throw new \Exception("mysqli::rollback(),error=".mysqli_error($this->conn).",errno=".mysqli_errno($this->conn));
			}
			$this->in_transaction = true;
			return true;
		}


	}
