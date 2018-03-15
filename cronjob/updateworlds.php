<?php
define("SYSTEM", time());
/* Tibia Website 
Updates Worlds existing including location, type and name.
Load config */
include('../app/config.php');
/* Load router */
include('../core/lib/mysql.php');
$db = new Database(array("host" => $config["dbCredentials"]["host"], "user" => $config["dbCredentials"]["user"], "pass" => $config["dbCredentials"]["pass"], "database" => $config["dbCredentials"]["database"]));
/* Tibia Stuff

Updated to use the TíbiaData.com API */
$json = file_get_contents("https://api.tibiadata.com/v2/worlds.json");
$worlds_json = json_decode($json, TRUE);

