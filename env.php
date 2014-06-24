<?php

/* ==========================
 * env 
 * ==========================
 */

interface IEnv {

	/* ============================
	 * io objects handler functions 
	 * ============================
	 */
	public function load($io_config);
	public function all_io();
	public function __get($name);
	public function __set($name, $value);


	public function set_uri_router(IUriRouter $uri_router);
	public function set_err_handler(IErrHandler $err_handler);
	public function set_class_loader(IClassLoader $class_loader);

	/* =============================
	 * environment instance manage
	 * =============================
	 */
	public static function curr();
	public function set_as_curr_env();

}

/* ============================
 * uri router 
 * ============================
 */
interface IUriRouter {
	public function request($uri);		
	public function query($uri, array $params=array());

	const URI_OP_ALLOW_HOST = 0;
	const URI_OP_ALLOW_METHOD = 1;
	public function uri_option($uri, $op_name, $op_value);
}

/* ============================
 * error handler 
 * ============================
 */
interface IErrHandler {
	const UNKNOWN_ERROR = -1;

	const ERR_OUT_NONE = 1;
	const ERR_OUT_ERROR = 2;
	const ERR_OUT_ALL = 3;

	const HALT_STYLE_DIE = 1 ;
	const HALT_STYLE_EXCEPTION = 2;

	public function set_halt_style($style);		
	public function set_err_out_type($type);	
	public function halt($errno);				/* $env->err_handler->halt(0) //success exist */
	public function error($errmsg, $errno=null);	/* $env->err_handler->error("something wrong", -1) */
	public function warning($errmsg, $errno);	/* $env->err_handler->error("something not good ", -1)  */
}

interface IPhpErrHandler {
	public function php_err_handler($errno, $errmsg, $errfile, $errline);
}


/* =============================
 * class auto loader 
 * =============================
 */
interface IClassLoader {
	public function create($class_name, array $construct=array(), array $properties=array());	/* $http = $env->class_loader->create("Tcp", array("127.0.0.1","8080"),array("method"=>"POST"));  */
}

interface ILog {
	public function log();
}

interface IPregInput {
	public function get($key, $preg_pettern=null);
	public function all($key_preg_pettern=null, $val_preg_pettern=null);
}

interface IHashOutput {
	public function set($key, $value);
}

interface IBuffer {
	public function clean();
	public function flush();
}


/**********************************  classes ********************************/

class Env implements IEnv, IErrHandler, IClassLoader, IUriRouter {
	private $uri_router ;
	private $err_handler;
	private $class_loader;

	private $io = array(
		'stderr' => null, 		/* ILog */
		'web' => null,
		'get' => null,
		'post' => null,
		'request' => null,
		'session' => null,
		'cookie' => null
	);

	/* ============================
	 * io objects handler functions 
	 * ============================
	 */
	/* load io configuration, and create io objects in the config
	 *
	 * @param   $io_config, array, structure like this:  array( "class" => class name,  "construct_params" => array(construct params,...), "properties" => array( key => val, ...))
	 * @return  null
	 */
	public function load($io_config){
		foreach ($io_config as $io => $cfg) {
			$this->assert(isset($cfg['class']), "no class found in io_config");
			$this->assert(class_exists($cfg['class']), "class=".$cfg['class']." dosen't exist");
			$this->assert(isset($cfg['construct_params']) && is_array($cfg['con_params']), "wrong construct params for class=".$cfg['class']);
			$this->assert(isset($cfg['properties']) && is_array($cfg['properties']), "wrong properties for class=".$cfg['class']);

			$class = $cfg['class'];
			$properties = $cfg['properties'];
			$construct_params = $cfg['construct_params'];
			$object = $this->create($class, $construct_params, $properties);
			$this->io[$io] = $object;
		}
	}

	public function all_io(){
		return $this->io;
	}

	/* get io
	 */
	public function __get($name){
		if (isset($this->io[$name])) {
			return $this->io[$name];
		} else {
			return false;
		}
	}

	/* set io
	 */
	public function __set($name, $obj){ 
		$this->io[$name] = $obj;
	}


	/* ============================
	 * uri router 
	 * ============================
	 */
	public function set_uri_router(IUriRouter $uri_router){
		$this->uri_router = $uri_router;
	}
	public function get_uri_router(){return $this->uri_router;}
	public function request($uri){
		return $this->uri_router->request($uri);
	}

	public function query($uri, array $params=array()){
		return $this->uri_router->query($uri, $params);
	}

	/* =============================
	 * uri request options
	 * =============================
	 */
	public function uri_option($uri, $op_name, $op_value){
		/* uri_option('/example/abc', 'allow_host', 'www.google.com')	---   allow other sites to access it 
		 * uri_option('/example/abc', 'allow_method', 'post')			---	  allow method to be used 
		 */
		$this->uri_router->uri_option($uri, $op_name, $op_value);
	}
	public function safe_request($uri){
		try {
			$this->uri_router->request($uri);
		}catch(Exception $e){
			;
		}
	}

	/* ============================
	 * error handler 
	 * ============================
	 */
	private $halt_style = self::HALT_STYLE_DIE;

	public function set_err_handler(IErrHandler $err_handler){
		$this->err_handler = $err_handler;
	}

	public function get_err_handler(){return $this->err_handler;}

	public function set_stderr(ILog $stderr){ $this->stderr = $stderr; }

	public function halt($errno, $errmsg = null){
		$this->err_handler->halt($errno, $errmsg);
	}

	public function error($errmsg, $errno=self::UNKNOWN_ERROR){
		$this->err_handler->error($errmsg, $errno);
	}
	public function warning($errmsg, $errno=self::UNKNOWN_ERROR){
		$this->err_handler->warning($errmsg, $errno);
	}
	public function assert($assertion, $errmsg, $errno=self::UNKNOWN_ERROR){
		$this->err_handler->assert($assertion, $errmsg, $errno);
	}

	public function set_halt_style($style){
		$this->err_handler->set_halt_style($style);
	}

	public function set_err_out_type($type){
		return $this->err_handler->set_err_out_type($type);
	}


	/* =============================
	 * class auto loader 
	 * =============================
	 */
	public function set_class_loader(IClassLoader $class_loader){
		$this->class_loader = $class_loader;
	}
	public function get_class_loader(){ return $this->class_loader; }
	public function create($class_name, array $construct_params=array(), array $properties=array()){ 
		return $this->class_loader->create($class_name, $construct_params, $properties);
	}


		/* =============================
		 * environment instance manage
		 * =============================
		 */
		private static $env = null;

	public static function curr(){
		if (self::$env == null) {
			return false;
		}
		return $env;
	}

	public function __construct($config = array()){		/* new Env();   new Env(array("stderr"=>"Err"));   new Env(array())*/
		$this->stderr = new EnvStdErr();
		$this->set_err_handler(new EnvErrHandler($this));
		$this->set_class_loader(new EnvClassLoader($this));
		$this->set_uri_router(new EnvUriRouter($this));

		$this->load($config);

		$this->web = new EnvWeb();
		$this->get = $this->web->get;
		$this->post = $this->web->post;
		$this->request = $this->web->request;
		$this->session = $this->web->session;
		$this->cookie = $this->web->cookie;

		return ;
	}

	public function set_as_curr_env(){
		self::$env = $this;
	}

}

class EnvStdErr implements ILog{
	public function log(){
		echo implode("|", func_get_args());
		echo "<br/>";
	}
}

class EnvErrHandler implements IErrHandler, IPhpErrHandler {
	private $env = null;
	private $halt_style = self::HALT_STYLE_DIE;
	private $err_out_type = self::ERR_OUT_ALL;

	public function __construct(IEnv $env){
		$this->env = $env;
		set_error_handler(array($this, 'php_err_handler'));
	}

	public function php_err_handler($errno, $errmsg, $errfile, $errline){
		if ($this->env && $this->env->stderr){
			$this->env->stderr->log($errno, $errmsg, $errfile, $errline);
		}
	}

	public function set_halt_style($style){
		switch ($style) {
		case self::HALT_STYLE_DIE:
		case self::HALT_STYLE_EXCEPTION:
			$this->halt_style = $style;
			break;

		default:
			break;
		}
	}

	public function halt($errno, $errmsg=null){
		switch ($this->halt_style){
		case self::HALT_STYLE_DIE:
			exit($errno);
		case self::HALT_STYLE_EXCEPTION:
			throw new Exception($errmsg, $errno);
		default:
			die("wrong halt style=".$this->halt_style);
		}
	}

	public function set_err_out_type($type){
		switch ($type) {
		case self::ERR_OUT_NONE :
		case self::ERR_OUT_ERROR :
		case self::ERR_OUT_ALL:
			$this->err_out_type = $type;
			return true;
			break;

		default:
			return false;
			break;
		}
	}

	public function error($errmsg, $errno=self::UNKNOWN_ERROR){
		if ($this->evn == null || $this->env->stderr == null) {
			die("[no stderr found] errmsg=".$errmsg.",errno=".$errno);
		}
		$output_error = false;
		if ($this->err_out_type != self::ERR_OUT_NONE) {
			$this->env->stderr->log($errno, $errmsg);
		}
		$this->halt($errno, $errmsg);
	}

	public function warning($errmsg, $errno=self::UNKNOWN_ERROR){
		if ($this->env != null && $this->env->stderr != null && $this->err_out_type == self::ERR_OUT_ALL) {
			$this->env->stderr->log($errmsg, $errno);
			return true;
		}
		return false;
	}

	public function assert($assertion, $errmsg, $errno=self::UNKNOWN_ERROR){
		if ($assertion != true) {
			if ($this->env == null || $this->env->stderr == null){
				die("[no stderr found] errmsg=".$errmsg.", errno=".$errno);
			}
			$this->env->stderr->log($errno, $errmsg);
			$this->halt($errno, $errmsg);
		}
	}

}


class EnvClassLoader implements IClassLoader{
	public function __construct(IEnv $env){}
		public function create($class_name, array $construct=null, array $properties=null){
			if (!class_exists($class_name)){
				return false;
			}
			$obj = false;
			$construct = array_values($construct);
			switch (count($construct)) {
			case 0:
				$obj = new $class(); break;
			case 1:
				$obj = new $class($construct[0]); break;
			case 2:
				$obj = new $class($construct[0], $construct[1]); break;
			case 3:
				$obj = new $class($construct[0], $construct[1], $construct[2]); break;
			case 4:
				$obj = new $class($construct[0], $construct[1], $construct[2], $construct[3]); break;
			default:
				return false;
				break;
			}
			foreach ($properties as $key => $val) {
				if (isset($obj->$key)) {
					$obj->$key = $val;
				}
			}
			return $obj;
		}
}

/*  uri router */
class EnvUriRouter implements IUriRouter{
	private $env ;
	public function __construct(Env $env){
		$this->env = $env;
	}

	public function uri_option($uri, $op_name, $op_value){
	}

	/* uri router
	 *
	 * @param	$uri, string , three formate is acceptable:
	 *          1. abc/def.json   		-- output format is specified (like json here);
	 *          2. abc/def.xml-json		-- output format and php-input format is specified (like "xml" as php-input-format, "json" as output-format here );
	 */
	public function request($uri){

		$env = $this->env;
		/* fetch request path */
		$env->assert(preg_match('/^\/{0,1}(?<mod>[\w-_]+)\/(?<act>[\w-_]+)(\.(?<in_out_format>\w+)){0,1}.*$/', $uri, $match), "wrong uri, uri=".$uri);

		/* fetch job path */
		$path = $match['mod'] . "/" . $match['act'];

		/* fetch input-output format */
		if (isset($match['in_out_format'])) {
			$in_out_format = $match['in_out_format'];
		} else {
			$in_out_format = 'json';
		}

		if (count($inout = explode("-", $in_out_format)) == 2) {
			$input_format = $inout[0];
			$output_format = $inout[1];

		} else {
			$input_format = 'txt';
			$output_format = $inout[0];
		}

		if (!isset($env->web)) {
			$env->web = new EnvWeb();
		}

		$env->web->output_format = $output_format;
		if ($env->web->output_format == 'html'){
			$env->web->output_tpl = $path . ".html";
		}
		$env->web->input_format = $input_format;
		$result = $env->query($path, $env->web->all());

		if (is_array($result)) {
			foreach ($result as $key => $val) {
				$env->web->set($key, $val);
			}
		} else if (is_string($result))  {
			$env->web->set(0, $result);

		} else {
			$env->assert(false, "query got a wrong return value, path=".$path);
		}

		$env->web->flush();

	}


	/* job router 
	 *
	 * @param	$uri, string , in format like this:  abc/def 
	 * @param	$params, params array
	 * @return	mixed
	 */
	public function query($uri, array $params=array()){

		/* fetch request path */
		$this->env->assert(preg_match('/^\/{0,1}(?<mod>[\w-_]+)\/(?<act>[\w-_]+)(\.(?<in_out_format>\w+)){0,1}.*$/', $uri, $match), "wrong uri, uri=".$uri);

		/* fetch job path */
		$job_path = $match['mod'] . "/" . $match['act'];

		$job = explode("/", $job_path);

		$job_class = ucfirst($job[1]);
		if (!class_exists($job_class)) {
			$this->env->assert(file_exists($job_path.".php"), "no job file found, file=".$job_path.".php");
			include $job_path.".php";
			$this->env->assert(class_exists($job_class), "wrong job class, path=".$job_path.",class=".$job_class);
		}
		$this->env->assert(method_exists($job_class, 'run'), "job class has not implement 'run' method yet, path=".$job_path);
		$job = new $job_class;
		return $job->run($params);
	}
}


class EnvPreg implements IPregInput{
	private $input = array();

	public function set_input(array $input) {
		$this->input = $input;
	}

	public function get($key, $pettern=null){
		if (!isset($this->input[$key])) {
			return false;
		}

		if (!$pettern) {
			return $this->input[$key];
		} 

		if (@preg_match($this->input[$key], $pettern)) {
			return $this->input[$key];
		}

		return false;
	}


	public function all($key_pettern = null, $val_pettern = null) {
		$result = array();
		if ($key_pettern == null && $val_pettern == null) {
			return $this->input;
		}

		if ($key_pettern != null && $val_pettern == null) {
			foreach ($this->input as $key => $val){
				if (preg_match($key, $key_pettern)) {
					$result[$key] = $val;
				}
			}
			return $result;
		}

		if ($key_pettern != null && $val_pettern != null){
			foreach ($this->input as $key => $val) {
				if (preg_match($key, $key_pettern) && preg_match($val, $val_pettern)) {
					$result[$key] = $val;
				}
			}
			return $result;
		}

		if ($key_pettern == null && $val_pettern != null) {
			foreach ($this->input as $key => $val) {
				if (preg_match($val, $val_pettern)) {
					$result[$key] = $val;
				}
			}
			return $result;
		}
	}
}

class EnvWeb implements IBuffer, IHashOutput, IPregInput{
	private $output = array();
	private $input_list = array();

	public $output_format = 'json';
	public $output_tpl = null;	/* $output_tpl should be not null when $output_format == 'html' */

	private $input_format = 'txt';

	public function __construct(){
		$this->input_list['get'] = new EnvGet();
		$this->input_list['post'] = new EnvPost();
		$this->input_list['cookie'] = new EnvCookie();
		$this->input_list['session'] = new EnvSession();
		$this->input_list['request'] = new EnvRequest();
		$this->input_list['phpinput'] = new EnvPhpInput($this->input_format);
	}

	public function __set($name, $value){
		$this->$name = $value;
		if ($name == 'input_format'){
			$this->input_list['phpinput'] = new EnvPhpInput($this->input_format);
		}
	}

	public function __get($name){
		switch ($name) {
		case 'input_format':
			return $this->input_format;
			break;

		case 'get':
		case 'post':
		case 'cookie':
		case 'session':
		case 'request':
		case 'phpinput':
			return $this->input_list[$name];
			break;

		default:
			return false;
		}
	}

	public function get($key, $pettern=null){
		foreach ($this->input_list as $io){
			if (($res = $io->get($key, $pettern)) !== false) {
				return $res;
			}
		}
		return false;
	}

	public function all($key_pettern=null, $val_pettern=null){
		return $this->input_list['request']->all($key_pettern, $val_pettern);
	}

	public function set($key, $value){
		$this->output[$key] = $value;
		return true;
	}

	public function clean(){
		$tmp = $this->output;
		$this->output = array();
		return $tmp;
	}

	public function flush(){
		switch ($this->output_format){
		case 'json':
			echo json_encode($this->output);
			break;

		case 'txt':
			echo implode("", $this->output);
			break;

		case 'xml':
			echo toxml($this->output);
			break;

		case 'html':
			echo tohtml($this->output, $this->output_tpl);
			break;

		default:
			trigger_error("unknown output_format,output_format=".$this->output_format, E_USER_WARNING);

		}
		$this->output = array();
	}
}

class EnvPhpinput extends EnvPreg{
	public function __construct($format){
		$contents = @file_get_contents("php://input");
		switch (strtolower($format)) {
		case 'json' : 
			$this->set_input(json_decode($contents, true));
			break;

		case 'xml':	/* simple xml */
			$this->set_input(json_decode(json_encode(simplexml_load_file("phpinput://")), true));
			break;

		case 'txt':
			$this->set_input(array($contents));
			break;
		}
	}
}

class EnvCookie extends EnvPreg {
	public function __construct(){
		if (is_array($_COOKIE)){
			$this->set_input($_COOKIE);
		}
	}
}

class EnvSession extends EnvPreg implements IHashOutput{
	public function __construct(){
		if (!isset($_SESSION)) {
			session_start();
		}
		if (is_array($_SESSION)) {
			$this->set_input($_SESSION);
		}
	}

	public function set($key, $val){
		if (!isset($_SESSION)) {
			session_start();
		}
		$_SESSION[$key] = $val;
	}
}

class EnvRequest extends EnvPreg{
	public function __construct(){
		if (is_array($_REQUEST)) {
			$this->set_input($_REQUEST);
		}
	}
}

class EnvPost extends EnvPreg {
	public function __construct(){
		if (is_array($_POST)) {
			$this->set_input($_GET);
		}
	}
}

class EnvGet extends EnvPreg {
	public function __construct(){
		if (is_array($_GET)) {
			$this->set_input($_GET);
		}
	}
}

