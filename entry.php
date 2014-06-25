<?php
include __DIR__ . "/core/env.php";
$env = new Env(array());
$env->root = __DIR__;
$env->safe_request($_SERVER['REQUEST_URI']);
