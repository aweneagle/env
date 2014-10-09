<?php
namespace env\globals;
	/* variable	$_SERVER
	 *
	 *
	 * @example
	 *	env()->server = new env_var_server;
	 *	env()->server->REQUEST_URI;
	 *	env()->server->REQUEST_METHOD;
	 *	......
	 *
	 */

class server {
	private $server_env = array();
	public function __construct(){
		$this->server_env = $_SERVER;
	}
	public function __get($name) {
		if (isset($this->server_env[$name])) {
			return $this->server_env[$name];
		}
		return false;
	}

	public function __set($name, $value){
		$this->server_env[$name] = $value;
		return true;
	}

	public function __isset($name) {
		return isset($this->server_env[$name]);
	}

}
