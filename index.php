<?php

// MAIN CONTROLLER

// common settings
ini_set('display_errors',1);
error_reporting(E_ALL);

session_start();

// autoloading files
define('ROOT', dirname(__FILE__));
require_once(ROOT . '/components/Autoload.php');
spl_autoload_register('autoloader');

$definitions = ROOT . '/config/definitions.php';
include($definitions);

//Router call
$router = new Router();
$router->run();