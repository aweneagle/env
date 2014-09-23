<?php
namespace module;
class entry {
	public function run(){
		$request_id = uniqid();
		try {

			$input = env()->call('/read');

			env()->call('/log/request', array('reqid'=>$request_id, 'mod'=>$input['mod'], 'format'=>$input['format'], $input['data']));
			$data = env()->call($input['mod'], $input['data']);
			env()->call('/log/response', array('reqid'=>$request_id, $input['mod'], $input['format'], $data));

			env()->call('/write', array('data'=>$data, 'format'=>$input['format'], 'mod'=>$input['mod']));

		} catch (Exception $e){

			env()->call('/log/error', array('err'=>$e, 'reqid' => $request_id));
		}

	}
}
