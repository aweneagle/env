<?php
namespace module;
class read {
	public function run(){
		switch (env()->server->REQUEST_METHOD) {
		case 'GET' :
			$in = new \env\hash\get();
			break;

		case 'POST':
			$in = new \env\hash\post();
			break;

		default:
			$in = new \env\hash\console();
			break;

		}
		return $in->all();
	}
}
