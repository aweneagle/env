<?php
	interface ihash {

		/* get value 
		 * 
		 * @param key, string
		 *
		 * @return false, if key not exists;  mixed , if success
		 */
		public function get($key);


		/* set value 
		 *
		 * @param key, string
		 * @param value, mixed
		 *
		 * @return always true
		 */
		public function set($key, $val);


		/* key exists 
		 *
		 * @param	key, string
		 *
		 * @return true or false
		 */
		public function exists($key);

	}

