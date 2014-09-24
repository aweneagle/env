<?php
namespace adm {
	class read {
		public function run(){
			$from_console = false;
			switch (env()->server->REQUEST_METHOD) {
			case 'GET' :
				$in = new \env\hash\get();
				break;

			case 'POST':
				$in = new \env\hash\post();
				break;

			default:
				$in = new \env\hash\console();
				$from_console = true;
				break;

			}
			if (!$from_console) {
				$uri = env()->server->REQUEST_URI;
			} else {
				$uri = $in->get("uri");
			}
			env()->router->explain($uri, $module_path, $output);

			return array(
				'env' => 'app',
				'mod'	=>	$module_path,
				'format'=>	$output,
				'data'	=>	$in->all()
			);
		}
	}
}
