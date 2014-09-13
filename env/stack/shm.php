<?php
	interface istack {

		/* push data 
		 *
		 * @param $data, string or array, 'false' could not be pushed 
		 *
		 * @return true , if stack is full, false is returned;
		 */
		public function push($data);


		/* pop data 
		 *
		 * @return string or array,  if stack is empty , false is returned;
		 */
		public function pop();
	}

