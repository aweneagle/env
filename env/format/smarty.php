<?php
	namespace env\format;
	class smarty implements \env\format\iformat {
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
		 * @param 	data, param as array
		 *
		 * @return 	always true 
		 */
		public function write(array $data){
			if (!class_exists("\\Smarty")) {
				require ENV_ROOT . "/env/smarty/libs/Smarty.class.php";
			}
			$smarty = new \Smarty();
			$smarty->compile_dir = ENV_ROOT . "/env/smarty/templates_c/";
            $smarty->config_dir = ENV_ROOT . "/env/smarty/configs/";
            $smarty->cache_dir = ENV_ROOT . "/env/smarty/cache/";
			$smarty->assign($data);
			return $smarty->fetch($this->tpl_dir . "/" . $this->tpl);
		}

	}

