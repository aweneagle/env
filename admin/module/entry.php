<?php
namespace adm {
	class entry {
		public function run(){
			$request_id = uniqid();
			try {

				echo "=============== from admin ===============\n";
				$input = env()->call('/read');

				/*

				
				env("MON_ENV")->wakeup();		// APP_ENV sleep, MON_ENV woke up 
			   	env()->load(require(APP_ROOT . "/../monitor/config.php"));
				env()->call("/entry");
				env()->sleep();					// MON_ENV sleep, APP_ENV woke up
				env("APP_ENV")->sleep();		// failed
				env("MON_ENV")->sleep();		// MON_ENV sleep, APP_ENV woke up
				env("MON_ENV")->destroy();		// MON_ENV destroy, APP_ENV woke up
				 */
				

				env()->call('/write', array('mod'=>$input['mod'], 'format'=>$input['format'], 'data'=>$data));

			} catch (Exception $e){

				env()->call('/log/error', array('err'=>$e, 'reqid' => $request_id));
			}

		}
	}
}
