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
/* Tibia Stuff */
$worldsupdated = 0;
define("APPLICATION", "../app/");
include('../core/lib/common.php');
include('../core/lib/tibiaparse.php');
$tibia = new TibiaParser;
$updateDate = mktime(4, 0, 0, date("m"), date("d"), date("Y"));
/* Select 500 profiles sorted by profiledupdated column */
$db->query("SELECT * FROM players WHERE deleted = 0 ORDER BY profileupdated ASC LIMIT 10000");
$players = $db->resultset();
foreach($players as $player) {
    $name = str_replace(" ", "%20", $player["name"]);
    $profileData = json_decode(file_get_contents("https://api.tibiadata.com/v2/characters/".$name.".json"), TRUE);
    $profileData = $profileData["characters"];
    if(isset($profileData["error"])){
        # Player has been deleted.
        $db->query("UPDATE players SET deleted = 1 WHERE id = :charid");
            $db->bind(":charid", $player["id"]);
        $db->execute();
        $db->query("Insert into players_deleted (charid, deleteddate) VALUES(:charid, :updated)");
            $db->bind(":charid", $player["id"]);
            $db->bind(":updated", time());
        $db->execute();
    } else {
        # Player exists
        $vocation = getVocationId($profileData["data"]["vocation"]);
        $characterSex = getSexId($profileData["data"]["sex"]);
        echo $characterSex.' - '.$profileData["data"]["sex"];
        foreach($profileData["deaths"] as $death){
            $date = strtotime($death["date"]["date"]);
            $db->query("SELECT id FROM player_deaths WHERE date = :date");
                $db->bind(":date", $date);
            $db->execute();
            if($db->rowcount() == 0){
                $db->query("INSERT INTO player_deaths (date, reason, level, charid) VALUES(:date, :reason, :level, :charid)");
                    $db->bind(":date", $date);
                    $db->bind(":reason", $death["reason"]);
                    $db->bind(":level", $death["level"]);
                    $db->bind(":charid", $player["id"]);
                $db->execute();
            }
        }
        $db->query("Update players SET profileupdated = :updated, vocation = :voc, level = :level, sex = :sex WHERE id = :id");
            $db->bind(":updated", time());
            $db->bind(":voc", $vocation);
            $db->bind(":level", $profileData["data"]["level"]);
            $db->bind(":sex", $characterSex);
            $db->bind(":id", $player["id"]);
        $db->execute();
    }

    $profileData = null;
}