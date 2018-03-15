<?php
/*
	Error reporting
*/
session_start();
if($_SERVER["HTTP_HOST"] == "localhost" or $_SERVER['REMOTE_ADDR'] == "79.136.69.128"){
	#define('ENVIRONMENT', 'development');
} else {
	#define('ENVIRONMENT', 'production');
}
	define('ENVIRONMENT', 'development');
if(defined('ENVIRONMENT')){
	switch (ENVIRONMENT) {
		case 'development':
			error_reporting(E_ALL);
		break;
		case 'testing':
		case 'production':
			error_reporting(0);
		break;
		default:
			exit('The application environment is not set correctly.');
	}
}
/*
	ROOT
*/
define('ROOT', dirname(__FILE__));
/*
	SYSTEM
*/

define('SYSTEM', ROOT.'/core/');

/*
	APPLICATION
*/
define('APPLICATION', ROOT.'/app/');
/*
 Since the site operates compared to German DST etc, we set timezone to reflect Berlin time.
 */
date_default_timezone_set("Europe/Berlin");
/* Checks */
if(!is_dir(SYSTEM)){
	die("System directory does not exist!");
}
if(!is_dir(APPLICATION)){
	die("Application folder does not exist!");
}

/* Bootstrap */
if(file_exists(SYSTEM.'bootstrap.php')){
	include(SYSTEM.'bootstrap.php');
} else {
	die("Failed to load bootstrap file.");
}

