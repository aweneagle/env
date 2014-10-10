<?php

require './env/env.php';
env()->stderr = new \env\stream\echo_output("json");
//env()->stderr = new \env\stream\echo_buffer("json");
$conf = new \env\lib\config("./test.conf");
print_r($conf->to_array());
