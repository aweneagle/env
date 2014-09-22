<?php
namespace group;
class files {
	public function run($params){
		$files = env()->files->all_file_names();

		env()->files['icon']->name;
		env()->files['icon']->type;
		env()->files['icon']->mimetype;

		return env()->files->get_name('icon');
	}
}
