<?php

	interface icaller {

		/* call a module
		 *
		 * @param	script_filename, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($script_filename, array $params = array());
	}

