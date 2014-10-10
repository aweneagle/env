<?php

require './env/env.php';
//env()->stderr = new \env\stream\echo_output("json");
//env()->stderr = new \env\stream\echo_buffer("json");

env()->stderr = new \env\lib\io_buffer( 
	new \env\map\env(env(), "/module/log/error"),	/* turn info array into fixed-format array */
	new \env\format\csv("|"),						/* turn fixed-format array into  string */
	new \env\stream\stderr()						/* write in string line */
);

$stdin = new \env\lib\io_buffer(
	new \env\stream\stdin(),						/* read in string */
	new \env\format\xml(),							/* turn string into array */
	new \env\map\env(env(), "/module/read")			/* turn array into fixed-format data */
);

$stdout = new \env\lib\io_buffer(
	new \env\map\env(env(), "/module/write")		/* turn info array into fixed-format array */
	new \env\format\smarty("/tpl/some/example.tpl"),/* turn fixed-format array into  string */
	new \env\stream\stdout(),						/* write in string line */
);

$stdout->write(env()->call("/module/entry", $stdin->read()));

$conf = new \env\lib\config("./test.conf");
print_r($conf->to_array());
