<?php
namespace app {
	class entry {
		public function run(){
			$request_id = uniqid();
			try {

				$input = env()->call('/read');
				$data = array();

				if ($input['mod'] != '/entry') {

					if (env()->open_request_log) {
						env()->call("/log/request", array($request_id, $input['mod'], $input['format'], $input['data']));
					}
					
					//env()->log_in->write(array($request_id, $input['mod'], $input['format'], $input['data']));
					$data = env()->call($input['mod'], $input['data']);
					//env()->log_out->write(array($request_id, $input['mod'], $input['format'], $data));

					if (env()->open_response_log) {
						env()->call("/log/response", array($request_id, $input['mod'], $input['format'], $data));
					}

				}

				//env()->call('/write', array('mod'=>$input['mod'], 'format'=>$input['format'], 'data'=>$data));

			} catch (\Exception $e){

				env()->call('/log/error', array('exp'=>$e, 'reqid' => $request_id));
			}

		}
	}
}
