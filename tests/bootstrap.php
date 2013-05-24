<?php
$loader = require __DIR__ . "/../vendor/autoload.php";
$loader->add('Builder', __DIR__);

set_include_path(
	__DIR__ . "/../vendor/kodova/hamcrest-php/hamcrest" . PATH_SEPARATOR .
	get_include_path()
);

require_once 'hamcrest.php';