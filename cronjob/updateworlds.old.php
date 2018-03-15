<?php
define("SYSTEM", time());
/* Tibia Website 
Updates Worlds existing including location, type and name.
Load config */
include('../app/config.php');
/* Load router */
include('../core/lib/mysql.php');
$db = new Database(array("host" => $config["dbCredentials"]["host"], "user" => $config["dbCredentials"]["user"], "pass" => $config["dbCredentials"]["pass"], "database" => $config["dbCredentials"]["database"]));
/* Tibia Stuff */
$error = false;
include('../core/lib/tibiaparse.php');
$tibia = new TibiaParser;
$updatedworlds = 0;
$worlds = $tibia->getWorlds();
$updateDate = mktime(4, 0, 0, date("m"), date("d"), date("Y"));
foreach($worlds as $w){
	$db->query("SELECT id, updated FROM worlds WHERE name = :name");
		$db->bind(":name", $w["name"]);
	$data = $db->single();
	if(!$data){
		/* Save previously unregistred world */
		$db->query("INSERT INTO worlds (id, name, updated, type, location) VALUES(null, :name, :updated, :type, :location)");
			$db->bind(":name", $w["name"]);
			$db->bind(":updated", $updateDate);
			$db->bind(":type", $w["worldtype"]);
			$db->bind(":location", $w["location"]);
		$db->execute();
		$updatedworlds++;
	} elseif($data["updated"] <= $updateDate){
		/* Update world */
		$db->query("UPDATE worlds SET updated = :updated, type = :type, location = :location WHERE id = :id");
			$db->bind(":updated", $updateDate);
			$db->bind(":type", $w["worldtype"]);
			$db->bind(":location", $w["location"]);
			$db->bind(":id", $data["id"]);
		$db->execute();
	}
}