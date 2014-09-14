<?php
	namespace \env\stream;
	class smarty implements \env\stream\istream {
		private $tpl = null;
		public function __construct($tpl) {
			$this->tpl = $tpl;
		}

		/* write in data 
		 *
		 * @param 	data, string or array, 'false' could not be pushed 
		 *
		 * @return 	always true 
		 */
		public function write($data){
			if (!is_array($data) || !is_string($data)) {
				throw new Exception("smarty::write()");
			}
			$smarty = new Smarty();
			if (!is_array($data)) {
				$data = array($data);
			}
			$smarty->display($this->tpl, $data);
		}


		/* read out data 
		 *
		 * @return 	string or array
		 */
		public function read(){
			return null;
		}
	}

