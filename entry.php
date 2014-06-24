<?php
include __DIR__ . "/env.php";
$env = new Env(array());
$env->request($_SERVER['REQUEST_URI']);
