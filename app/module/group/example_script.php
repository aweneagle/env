<?php
var_dump(phpinfo());die;
	class Task extends Thread{
		public function run() {
			$this->synchronized (function (){
				$query = env()->db0->query('select * from admin_user');
			});
			return array("a"=>"end");
		}
	}
	global $argv;
	global $argc;

	$new_task = new Thread();

	$new_task->start();
	return array("b"=>"end");


