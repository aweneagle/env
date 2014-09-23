<?php
namespace module;
class route {
	public function run(){
		env()->router->explain(env()->server->REQUEST_URI, $module_path, $output);
		return array('uri'=>env()->server->REQUEST_URI, 'mod'=>$module_path, 'out_format'=>$output);
	}
}
