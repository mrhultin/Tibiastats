<?php
if(!defined("SYSTEM")){
	die("Direct access is NOT allowed to this file.");
}
/* Load config */
include(APPLICATION.'config.php');
/* Include commonly used functions */
include(SYSTEM.'lib/common.php');
/* Setup router */
include(SYSTEM.'lib/router.php');
$router = new Router;
/* Load Controller class */
include(SYSTEM.'lib/mysql.php');
include(SYSTEM.'lib/controller.php');

$controller = new Controller();

/* All we need to get going should be achieved now */
if(!class_exists($controller->getController())){
    $controller->executeController($controller->getController());
}