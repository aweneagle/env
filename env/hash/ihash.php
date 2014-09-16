<?php
	namespace env\hash;
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


		/* set expired time
		 *
		 * @param key, string
		 * @param seconds, int, expired time , 0 means never expired
		 *
		 * @return trun on success;  false when key not exists
		 */
		public function expired($key, $seconds);


		/* key exists 
		 *
		 * @param	key, string
		 *
		 * @return true or false
		 */
		public function exists($key);


		/* get all values 
		 *
		 */
		public function all();


		/* delete value by key 
		 *
		 */
		public function delete($key);

	}

