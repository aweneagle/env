<?php
namespace app\test ;
class explain_curl {

	/* 
	 *  read dir's curl , and execute it as test case
	 * 
	 * @param	$params['file_path'],  curl file
	 * @return	$inputs 
	 */
	public function run($params){
		if (!isset($params['file_path'])) {
			env()->halt("test_explain::run(".$params['file_path'].")");
		}
		$file_dir = realpath(dirname($params['file_path']));
		$file_contents = file_get_contents($params['file_path']);
		$file_contents = array_filter(explode("\n", $file_contents));
		$inputs = array();
		$uri = null;
		foreach ($file_contents as $line) {
			switch (true) {
			case preg_match('/^url\s*=\s*(.*)$/', $line, $match) : {
				// trim ' and "	
				if ($match[1][0] == '\'' || $match[1][0] == '"') {
					$url = substr($match[1], 1, -1);
				} 
				if (!preg_match('/^(http|https):\/\/[^\/]+\/(?<uri>.*)$/', $url, $match)) {
					env()->halt("test_explain::run(".$url.")");
				};
				$uri = $match['uri'];
				if (preg_match('/^[^?]+\?(.+)$/', $uri, $match)) {
					foreach (array_filter(explode("&", $match[1])) as $chip) {
						if (count($chip = explode("=", $chip)) == 2) {
							list($key, $val) = $chip;
							$inputs[$key] = $val;
						}
					}
				};
				break;
			}
				
			case preg_match('/^data-urlencode\s*=\s*(.*)$/', $line, $match): {
				// trim ' and "	
				if ($match[1][0] == '\'' || $match[1][0] == '"') {
					$match[1] = substr($match[1], 1, -1);
				} 
				if (count($chip = explode("=", $match[1])) == 2) {
					list($key, $val) = $chip;
					$inputs[$key] = $val;
				} else if (count($chip = explode("@", $match[1])) == 2) {
					list($key, $file_path) = $chip;
					$inputs[$key] = file_get_contents($file_dir . "/" . $file_path);
				}	
				break;
			}


			default:
				break;

			}
		}

		return array('uri'=>$uri, 'inputs'=>$inputs);
	}
}
