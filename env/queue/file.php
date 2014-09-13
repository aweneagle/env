<?php
	interface iqueue {

		/* push data 
		 *
		 * @param data, string or array, 'false' could not be pushed 
		 *
		 * @return true;  if queue is full, false is returned;
		 */
		public function push($data);


		/* shift data 
		 *
		 * @return string or array;  if queue is empty, false is return
		 */
		public function shift();
	}

