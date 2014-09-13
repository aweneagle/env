<?php

	interface icaller {

		/* call a module
		 *
		 * @param	script_filename, string
		 * @param	params,	array
		 *
		 * @return	array
		 */
		public function call($script_filename, array $params = array());
	}

