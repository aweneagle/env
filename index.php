<?php


	/***********************
	 * configure 
	 **************************/
	require __DIR__ '/env.php';
	env()->root = __DIR__;

	env()->db0 = new \env\db\mysql_pdo("127.0.0.1", 3306);
	//env()->db0 = new \env\db\mysqli("127.0.0.1", 3306);
	//env()->db0 = new \env\db\csvdb("/data/csv");

	env()->cache0 = new \env\hash\memcached("127.0.0.1", 3307);
	//env()->cache0 = new \env\hash\redis("127.0.0.1", 3307);
	//env()->cache0 = new \env\hash\shm(0x11111, 0666);

	env()->stack0 = new \env\stack\redis("127.0.0.1", 3307, "message");
	//env()->stack0 = new \env\stack\shm(0x11111, 0666);
	//env()->stack0 = new \env\stack\file("/tmp/stackfile");

	env()->queue0 = new \env\queue\redis("127.0.0.1", 3307, "message");
	//env()->queue0 = new \env\queue\sockpipe("/tmp/pipe");
	//env()->queue0 = new \env\queue\file("/tmp/pipefile");

	env()->msg_queue = array(
		"chat" => new \env\queue\redis("127.0.0.1", 3307, "chat"),
		"message" => new \env\queue\redis("127.0.0.1", 3307, "message")
	);

	env()->stderr = new \env\stream\logfile("/tmp/env.error");
	//env()->stderr = new \env\stream\sockudp("127.0.0.1:9009");
	//env()->stderr = new \env\stream\websocket("127.0.0.1:9090");

	env()->pear_host0 = new \env\curl\websocket("127.0.0.1", 9999);
	//env()->pear_host0 = new \env\curl\http("127.0.0.1", 8800);
	//env()->pear_host0 = new \env\curl\fastcgi("127.0.0.1", 9000);
	//env()->pear_host0 = new \env\curl\mytcp("127.0.0.1", 1234);

	env()->pear_host1 = new \env\client\mytcp("127.0.0.1", 1234);
	//env()->pear_host1 = new \env\client\websocket("127.0.0.1", 8080);
	//env()->pear_host1 = new \env\client\fastcgi("127.0.0.1", 9000);



	/**************************
	 * set input 
	 *************************/

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		env()->stdin = new \env\hash\post();

	} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		env()->stdin = new \env\hash\get();

	} else {
		env()->stdin = new \env\hash\console();
	}


	/**************************
	 * explain uri 
	 * ***********************/
	env()->router = new \env\router\default();

	env()->router->explain($_SERVER['REQUEST_URI'], $output, $scrip_filename);

	switch ($output) {
	case 'xml':
		env()->stdout = new \env\stream\echo_output("xml");
		break;

	case 'json':
		env()->stdout = new \env\stream\echo_output("json");
		break;

	case 'html':
	case 'php' :
		env()->stdout = new \env\stream\smarty(substr($script_filename, 0, - strlen(".php")) . ".html");
		break;
	}



	/****************************
	 * call modules
	 * *************************/
	env()->caller = new \env\caller\obj();
	//env()->caller = new \env\caller\function();
	//env()->caller = new \env\caller\script();

	try {
		/* 
		 *  env()->call()   equals   env()->caller->call()
		 */
		$output = env()->call($script_info['script_filename'], env()->stdin->all());
		//$output = env()->caller->call($script_info['script_filename'], env()->stdin->all());

	} catch (Exception $e){
		env()->stderr->write($e->getMessage());
	}


	/****************************
	 * output
	 * **************************/

	env()->stdout->write($output);




	class example {
		public function run($req){
			$name = $req['name'];

			$info = array();
			foreach (env()->db0->query("select * form user where name=?", $name) as $row) {
				$info['age'] = $row['age'];
				$info['sex'] = $row['sex'];
				$info['hometown'] = $row['hometown'];
			}
			$age = env()->db0->get_value("select age from user where name=?", $name);

			env()->cache0->set($name, $info);
			env()->cache0->expired($name, 9000); 	/* after 9000 seconds , value will be expired */

			env()->msg_queue['info']->push(array("visitor"=>$name, "info"=>$info));
			$message = env()->msg_queue['message']->pop();
			$chat = env()->msg_queue['chat']->pop();

			return array('name'=>$name, 'msg'=>$message, 'chat'=>$chat, 'info'=>$info);
		}
	}


	class register {
		public function run(){
			return env()->caller->call('/module/example.php', array('name'=>'sky'));
		}
	}


