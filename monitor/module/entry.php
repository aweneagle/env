<?php
namespace mon {
	class entry {
		public function run(){
			$request_id = uniqid();
			try {

				echo "==================== from mon ================\n";
				$input = env()->call('/read');

				/*
				env()->call('/log/request', array($request_id, $input['mod'], $input['format'], $input['data']));
				$data = env()->call($input['mod'], $input['data']);
				env()->call('/log/response', array($request_id, $input['mod'], $input['format'], $data));
				*/

				env()->call('/write', array('reqid'=>$request_id, 'mod'=>$input['mod'],'format'=>$input['format'], 'data'=>$data));

			} catch (Exception $e){

				env()->call('/log/error', array('err'=>$e, 'reqid' => $request_id));
			}

		}
	}
}
