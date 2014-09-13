<?php
	interface iqueue {

		/* push data 
		 *
		 * @param $data, mixed, 'false' could not be pushed 
		 *
		 * @return false when queue is full; true when success 
		 */
		public function push($data);


		/* pop data 
		 *
		 * @return false when queue is empty; mixed when success
		 */
		public function pop();
	}

