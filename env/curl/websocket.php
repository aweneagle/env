<?php
	interface icurl {

		/* send request and fetch response 
		 *
		 * @param	$uri, string
		 * @param	$params, array 
		 *
		 * @return  string
		 */
		public function request($uri, array $params = array());


		/* set host
		 *
		 */
		public function host($host);


		/* set port 
		 *
		 */
		public function port($port);


		/* set option
		 *
		 * @param	$name, string
		 * @param	$value, string
		 */
		public function set_option($name, $value);
	}
