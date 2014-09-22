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

	}
