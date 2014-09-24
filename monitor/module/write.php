<?php
namespace mon {
	class write {
		public function run($data){
			echo "-------------- mon --------------\n";

			$output = @$data['format'];
			$data = @$data['data'];
			$module_path = @$data['mod'];

			switch ($output) {
			case 'json':
				$stdout = new \env\stream\echo_output("json");
				break;

			case 'text':
				$stdout = new \env\stream\echo_output('text');
				break;

			case 'html':
			case 'php':
			default:
				$stdout = new \env\stream\smarty($module_path.'.tpl');
				break;
			}
			$stdout->write($data);

		}
	}
}
