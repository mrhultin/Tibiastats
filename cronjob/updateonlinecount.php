<?php
/*
 * This Cronjob executes frequently updating to the total numbers of players online in Tibia.
 */
define("SYSTEM", time());
/* Tibia Website
Updates Worlds existing including location, type and name.
Load config */
include('../app/config.php');
/* Load router */
include('../core/lib/mysql.php');
$db = new Database(array("host" => $config["dbCredentials"]["host"], "user" => $config["dbCredentials"]["user"], "pass" => $config["dbCredentials"]["pass"], "database" => $config["dbCredentials"]["database"]));

$db->query("SELECT id FROM onlineplayers WHERE date >= :date");
    $db->bind(":date", time() - (15*60));
$db->execute();
if($db->rowcount() == 0) {
    $worlds_json = json_decode(file_get_contents("https://api.tibiadata.com/v2/worlds.json"), TRUE);
    if (isset($worlds_json["worlds"]["online"])) { // Let us make sure API isn't down or inaccurate
        $onlineNumber = $worlds_json["worlds"]["online"] * 1; // Make sure to format it to a integer
        $db->query("INSERT INTO onlineplayers (date, value) VALUES(:date, :value)");
            $db->bind(":date", time());
            $db->bind(":value", $onlineNumber);
        $db->execute();
    }
}