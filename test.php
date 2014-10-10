<?php

require './env/env.php';
env()->stderr = new \env\stream\echo_output("json");
env()->stderr = new \env\stream\echo_buffer("json");


/************************ solution 2. **************************/
env()->caller = new \env\caller\obj("./module/", "/module/");
$input = env()->call("/read");
$output = env()->call($input['mod'], $input);
env()->call("/write", $output);


$conf = new \env\lib\config("./test.conf");
print_r($conf->to_array());
