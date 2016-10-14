<?php
	namespace env\stream;
	interface istream {

		/* write in data 
		 *
		 * @param 	$data,  param as  string 
		 *
		 * @return 	always true 
		 */
		public function write($data);

		/* set filter maps
	   	 *
		 * @params	map1, function of type "func ($str_line)"
		 * @params	map2, function of type "func ($str_line)"
		 * @params	map3, ...
		 * ...
		 */
		public function set_maps();


		/* read out data
		 *
		 * @param	$len 
		 * @param	$str_buff
	 	 * @return 	len of string which is read out
		 */
		public function read($len, &$str_buf);


	}

