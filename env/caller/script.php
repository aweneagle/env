<?php
	namespace \env\caller;
	class script implements \env\caller\icaller {

		/* call a module
		 *
		 * @param	script_filename, string
		 * @param	params,	string or array
		 *
		 * @return	string or array
		 */
		public function call($script_filename, array $params = array()){
			$GLOBALS['ARGV'] = $params;
			return include_once($script_filename);
		}
	}

