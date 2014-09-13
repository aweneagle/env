<?php
	interface irouter {

		/* explain from `uri` into `script_filename`, `output_format`
		 *
		 * @param	uri, string
		 * @param	script_filename, string
		 * @param	output_format, string
		 *
		 * @return	always true	
		 */
		public function explain($uri, $script_filename, $output_format);


	}

