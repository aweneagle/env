<?php
	namespace env\router;
	interface irouter {

		/* explain from `uri` into `module_path`, `output_format`
		 *
		 * @param	uri, string
		 * @param	module_path, string
		 * @param	output_format, string
		 *
		 * @return	always true	
		 */
		public function explain($uri, &$module_path, &$output_format);


	}

