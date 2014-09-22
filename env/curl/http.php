<?php
	namespace env\curl;
	class http implements \env\curl\icurl {

		private $host ;
		private $port ;
		private $method;

		public function __construct($host, $port=80, $method='POST'){
			$this->host = $host;
			$this->port = $port;
			$this->method = $method;
		}

		/* send request and fetch response 
		 *
		 * @param	$uri, string
		 * @param	$params, array 
		 *
		 * @return  string
		 */
		public function request($uri, array $params = array()){
			if (!$ch = curl_init()) {
				throw new Exception("http::request($uri,".json_encode($params)."),err=".curl_strerror(curl_errno($ch)));
			}

			if ($this->port != 80) {
				$url = "http://". $this->host . ":" . $this->port . "/" . $uri;
			} else {
				$url = "http://". $this->host . "/" . $uri;
			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			switch ($this->method) {
			case 'GET':
				if (!empty($params)) {
					curl_setopt($ch, CURLOPT_URL, $url . "?" . http_build_query($params));

				} else {
					curl_setopt($ch, CURLOPT_URL, $url);

				}
				break;

			case 'POST':
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
				break;
			}
			$result = curl_exec($ch);
			if ($result === false) {
				throw new \Exception("http::request($uri,".json_encode($params)."),err=".curl_strerror(curl_errno($ch)));
			}
			return $result;
		}
	}
