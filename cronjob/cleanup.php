<?php
/* Profitzka Tibia Website */
/* This website comes with a few demonstrative functions for parsing information from Tibia.com, you may use this as you wish but you are required to put a clear credit to who made it if you do. */
/* Definitions */
set_time_limit(3500);
define("SYSTEM", sha1(time())); # This definition allows us to check for odd loadings and to make sure files cannot be directly accessed.
/* Load config */
include('../app/config.php');
/* Load router */
include('../core/lib/mysql.php');
$db = new Database(array("host" => $config["dbCredentials"]["host"], "user" => $config["dbCredentials"]["user"], "pass" => $config["dbCredentials"]["pass"], "database" => $config["dbCredentials"]["database"]));

/* Cleanup duplicate deletion entries */
$db->query("DELETE n1 FROM players_deleted n1, players_deleted n2 WHERE n1.id > n2.id AND n1.charid = n2.charid");
$db->execute();
/* Cleanup duplicate experience history entries */
$db->query("DELETE n1 FROM experiencehistory n1, experiencehistory n2 WHERE n1.id > n2.id AND n1.characterid = n2.characterid AND n1.date = n2.characterid");
$db->execute();
