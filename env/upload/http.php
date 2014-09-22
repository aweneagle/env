<?php
	namespace env\upload;
	class http implements \env\upload\iupload {

		private $host ;
		private $port ;
		private $files = array();

		public function __construct($host, $port=80){
			$this->host = $host;
			$this->port = $port;
		}



		/*
		 * add file which to be upload
		 *
		 * @param	file_path, string;	file path which to be uploaded
		 * @param	post_name, string;	input name of the form
		 * @param	mime_type, string;	mimetype of the file
		 * @param	file_name, string;	name of the file
		 * 
		 * @return	true or false
		 */
		public function add_file($file_path, $post_name = null, $mime_type = null, $file_name = null){
			$file = array();
			$file['file_path'] = $file_path;
			if (!$file_name) {
				$file_name = basename($file_path);
			}
			$file['mime_type'] = $mime_type;
			$file['file_name'] = $file_name;

			if ($post_name) {
				$this->files[$post_name] = $file;
			} else {
				$this->files[] = $file;
			}
			return true;
		}




		/* 
		 *	upload all files 
		 *
		 * @param	uri, string;		server uri, like "/upload/file.php"
		 * @param	form, array;		form variables
		 *
		 * @return	always true (if failed, exception should be thrown out)
		 */
		public function upload($uri, array $form = array()){

			if ($this->port != 80) {
				$url = "http://". $this->host . ":" . $this->port . "/" . $uri;
			} else {
				$url = "http://". $this->host . "/" . $uri;
			}

			if (!empty($form)) {
				$url .= '?' . http_build_query($form);
			}

			if (!$ch = curl_init($url)) {
				throw new Exception("http::request($url,".json_encode($form)."),err=".curl_strerror(curl_errno($ch)));
			}

			$curl_files = array();
			foreach ($this->files as $input_name => $info) {
				$uf = null;
				if (!$info['mime_type']) {
					$uf = curl_file_create($info['file_path']); 

				} else {
					$uf = curl_file_create($info['file_path'], $info['mime_type'], $info['file_name']);
				}
				$curl_files[$input_name] = $uf;
			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_files);

			$result = curl_exec($ch);
			if ($result === false) {
				throw new \Exception("http::request($url,".json_encode($form)."),err=".curl_strerror(curl_errno($ch)));
			}
			return $result;
		}
	}
