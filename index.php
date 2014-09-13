<?php


	/***********************
	 * configure 
	 **************************/
	require __DIR__ '/env.php';
	env()->root = __DIR__;

	env()->db0 = new \env\db\mysql_pdo("127.0.0.1", 3306);
	env()->db1 = new \env\db\mysql_pdo("127.0.0.1", 3306);

	env()->cache0 = new \env\hash\memcached("127.0.0.1", 3307);
	env()->cache1 = new \env\hash\memcached("127.0.0.1", 3307);

	env()->queue0 = new \env\queue\redis("127.0.0.1", 3307, "message");
	env()->queue1 = new \env\queue\redis("127.0.0.1", 3307, "list");

	env()->stderr = new \env\log\file("/tmp/env.error");
	env()->log = new \env\log\file("/tmp/env.log");



	/**************************
	 * set input 
	 *************************/

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		env()->stdin = new \env\read\post();

	} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		env()->stdin = new \env\read\get();

	} else {
		env()->stdin = new \env\read\console();
	}


	/**************************
	 * explain uri 
	 * ***********************/
	env()->router = new \env\router\object();

	env()->router->explain($_SERVER['REQUEST_URI'], $output, $scrip_filename);

	switch ($output) {
	case 'xml':
		env()->stdout = new \env\write\xml();
		break;

	case 'json':
		env()->stdout = new \env\write\json();
		break;

	case 'html':
	case 'php' :
		env()->stdout = new \env\write\html($script_filename . ".html");
		break;
	}



	/****************************
	 * call modules
	 * *************************/
	env()->caller = new \env\caller\func();

	try {
		$output = env()->caller->run($script_info['script_filename'], env()->stdin->read());

	} catch (Exception $e){
		env()->stderr->log($e->getMessage());
	}


	/****************************
	 * output
	 * **************************/

	env()->stdout->write($output);






	class example {
		public function run($req){
			$name = $req['name'];

			env()->db0->query("select * form user", $name);
			env()->db0->get_value("select * from user where name=?", $name, $name);

			env()->cache0->set("myname", $name);
			env()->cache1->set("yourname", $name+1);

			$message = env()->queue0->pop();

			$name += 1;
			return array('name'=>$name, 'msg'=>$message);
		}
	}


	class register {
		public function run(){
			$output = env()->caller->run('/example', array('name'=>'sky'));
			return $output;
		}
	}


