<?php
namespace group;
class upload {
	public function run($params){
		env()->files->move('icon', '/tmp/icon');

		env()->form->add_file("/tmp/icon", "name_uploaded_file", env()->files->get_type('icon'), 'icon.jpg');
		$data = env()->form->upload("/file_upload.php?v_cmd=1");
		if (preg_match('/file_upload_ok\("(.*)"\);/', $data, $match)) {
			return $match[1];
		} else {
			return false;
		}
	}
}
