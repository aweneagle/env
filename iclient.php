<?php
	interface iclient {

		const ERR_AGAIN = 1;				//when non_blocking, it mean's "try again"
		const ERR_CONN_REFUESED = 2;		//connection refused


		/* connect remote host
		 * 
		 * @param	host, string
		 * @param	port, int
		 *
		 * @return	true when success; false when failed , error message will be get by iclient::errmsg(), iclient::errno();
		 */
		public function connect($host, $port);



		/* close connection 
		 *
		 * @return always true
		 */
		public function close();



		/* send data 
		 *
		 * @param	data, string
		 *
		 * @return  true on success; false on failure , error message can be fetched by iclient::errmsg(), iclient::errno()
		 */
		public function send($data);



		/* recv data 
		 *
		 * @return	string	on success; false on failure, error message can be fetched by iclient::errmsg(), iclient::errno()
		 */
		public function recv();



		/* set blocking or non blocking
		 * 
		 * @param	block, boolean,  true means 'non blocking '; false means 'blocking'
		 * @return	always true
		 */
		public function set_nonblocking($block);



		/* last error message
		 *
		 * @return  string
		 */
		public function errmsg();



		/* last error number, see the const of iclient's definitions about ERR_xxx
		 *
		 * @return int
		 */
		public function errno();
	}
