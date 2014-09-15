<?php
	namespace env\stream;
	class smarty implements \env\stream\istream {
		private $tpl = null;
		private $tpl_dir = null;
		public function __construct($tpl, $tpl_dir = null) {
			$this->tpl = $tpl;
			if (!$tpl_dir) {
				$tpl_dir = ENV_ROOT . "/view/";
			}
			$this->tpl_dir = $tpl_dir;
		}

		/* write in data 
		 *
		 * @param 	data, string or array, 'false' could not be pushed 
		 *
		 * @return 	always true 
		 */
		public function write($data){
			if (!is_array($data) && !is_string($data)) {
				throw new \Exception("smarty::write()");
			}
			if (!class_exists("\\Smarty")) {
				require ENV_ROOT . "/env/smarty/libs/Smarty.class.php";
			}
			$smarty = new \Smarty();
			if (!is_array($data)) {
				$data = array($data);
			}
			$smarty->assign($data);
			$smarty->display($this->tpl_dir . "/" . $this->tpl);
		}


		/* read out data 
		 *
		 * @return 	string or array
		 */
		public function read(){
			return null;
		}
	}

