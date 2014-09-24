<?php
namespace app\test ;
class auto_test {

	/* 
	 *  read dir's curl , and execute it as test case
	 * 
	 * @param	$params['testcase_dir'],  dir in which curl files are ready
	 * @return	$inputs 
	 */
	public function run($params){
		if (!isset($params['testcase_dir']) || !is_dir($params['testcase_dir'])) {
			env()->halt("auto_test::run(".json_encode($params).")");
		}
		$this->call_test_cases(realpath($params['testcase_dir']));
	}

	private function call_test_cases($dir){
		foreach (scandir($dir) as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			if (is_dir($dir.'/'.$file)) {
				$this->call_test_cases($dir.'/'.$file);

			} else if (substr($file, -5) == '.curl') {
				$result = env()->call('/test/explain_curl', array('file_path' => $dir.'/'.$file));
				if ($result['uri']) {
					echo "========================  $dir/$file \n";
					try {
						env()->router->explain($result['uri'], $mod, $output_format);
						$data = env()->call($mod, $result['inputs']);
						env()->call('/write', array('data'=>$data, 'format'=>$output_format, "mod" => $mod));
					} catch (\Exception $e) {
						env()->call('/log/error', array('exp'=>$e, 'reqid'=>null));
					}
					echo "\n\n";
				}
			}
		}
	}
}
