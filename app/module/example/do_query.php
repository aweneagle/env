<?php
namespace app\example {
	class do_query {
		public function run($params){
			env()->session->set("1", "awen");
			return array(\env\lib\pinyin::str2pinyin("（中国"));
		}
	}
}
