<?php

/*************************************
 * config file
 *
 *
 * file contents look like :
 *
 *  #this is a comment
 *
 *  host = www.example.com		#this is a comment behind line data 
 *  port = 123456
 *  db_server = ( www.db.com, 3306 )
 *  server[0] = ( www.example.com, 123456 )
 *  server[1] = ( www.example2.com, 123456 )
 *	server[3] = now it's a string
 *
 *
 * it turns out to be an array :
 *
 *  $conf['host'] = 'www.example.com';
 *  $conf['port'] = '123456';
 *  $conf['db_server'] = array("www.db.com", "3306");
 *  $conf['server'][0] = array("www.example.com", "123456");
 *  $conf['server'][1] = array("www.example2.com", "123456");
 *  $conf['server'][3] = "now it's a string";
 *
 *
 */	
namespace env\lib;
class config {
	private $file_path;
	public function __construct($file_path) {
		$this->file_path = $file_path;
	}

	public function to_array(){
		if (!file_exists($this->file_path)){
			throw new \Exception("config::to_array()[".$this->file_path."]");
		}
		if (!$fp = fopen($this->file_path, 'r')) {
			throw new \Exception("config::to_array()[".$this->file_path."]");
		}
		$conf = array();
		while (!feof($fp)) {
			if ($line = trim(fgets($fp))) {
				if (($split = strpos($line, "#")) !== false) {

					// skip comment 
					$line = substr($line, 0, $split );

				}

				if (($split = strpos($line, "=")) !== false) {
					$key = trim(substr($line, 0, $split));
					$val = trim(substr($line, $split + 1));

					if (preg_match('/^\((.*)\)$/', $val, $match)) {
						$val = explode(",", $match[1]);
						foreach ($val as $i => $v) {
							$val[$i] = trim($v);
						}
					}

					if (preg_match('/(.*)\[(\w*)\]$/', $key, $match)) {
						$conf[trim($match[1])][trim($match[2])] = $val;

					}else{
						$conf[$key] = $val;
					}		


				}
			}
		}

		return $conf;
	}
}
