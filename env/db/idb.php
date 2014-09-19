<?php
	namespace env\db;
	interface idb {

		/*	db query , optional params
		 *	
		 *	@param	sql, string; use "?" to identify params
		 *	@param	params,  array
		 *
		 *	@return array 
		 */
		public function query($sql, array $params=array());


		/*  db query,  optional params
		 *
		 *  @param	sql, string; use "?" to identify params
		 *  @param	params, array
		 *
		 *  @return string
		 */
		public function get_value($sql, array $params=array());


		/*  db query,  optional params
		 *  get one row data
		 *
		 *  @param	sql, string; use "?" to identify params
		 *  @param	params, array
		 *
		 *  @return array
		 */
		public function get_row($sql, array $params=array());


		/* start transaction
		 *
		 * @return always true
		 */
		public function start_transaction();


		/* commit transaction
		 *
		 * @return  always true
		 */
		public function commit();


		/* roll back transaction 
		 *
		 * @return	always true
		 */
		public function rollback();


	}
