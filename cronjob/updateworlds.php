<?php
define("SYSTEM", time());
/* Tibia Website 
Updates Worlds existing including location, type and name.
Load config */
include('../app/config.php');
/* Load router */
include('../core/lib/mysql.php');
$db = new Database(array("host" => $config["dbCredentials"]["host"], "user" => $config["dbCredentials"]["user"], "pass" => $config["dbCredentials"]["pass"], "database" => $config["dbCredentials"]["database"]));
$updateDate = mktime(4, 0, 0, date("m"), date("d"), date("Y"));
/* Tibia Stuff
Updated to use the TíbiaData.com API */
$json = file_get_contents("https://api.tibiadata.com/v2/worlds.json");
$worlds_json = json_decode($json, TRUE);
foreach($worlds_json["worlds"]["allworlds"] as $world){
	$db->query("SELECT id, updated FROM worlds WHERE name = :name");
		$db->bind(":name", $world["name"]);
	$data = $db->single();
	if(!$data){
		/* Save previously unregistred world */
		$db->query("INSERT INTO worlds (id, name, updated, type, location) VALUES(null, :name, :updated, :type, :location)");
			$db->bind(":name", $world["name"]);
			$db->bind(":updated", $updateDate);
			$db->bind(":type", $world["worldtype"]);
			$db->bind(":location", $world["location"]);
		$db->execute();

	} elseif($data["updated"] <= $updateDate){
		/* Update world */
		$db->query("UPDATE worlds SET updated = :updated, type = :type, location = :location WHERE id = :id");
			$db->bind(":updated", $updateDate);
			$db->bind(":type", $world["worldtype"]);
			$db->bind(":location", $world["location"]);
			$db->bind(":id", $data["id"]);
		$db->execute();
	}
}