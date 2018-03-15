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
$db->query("SELECT * FROM players WHERE deleted = 0 ORDER BY profileupdated ASC LIMIT 1200");
$players = $db->resultset();
$lasttime = 0;
foreach($players as $player) {
    while(time() < $lasttime+1){
        usleep(50000); // Just delay each pageload by 1 second (0.5 seconds at a time, to ensure we don't waste time) so we don't timeout at CipSofts end (Their ddos protection might consider this an attack otherwise.)
    }
    $name = str_replace(" ", "%20", $player["name"]);
    $profileData = $tibia->characterInfo($name);
    #print_r($profileData);

    #echo '<a href="https://secure.tibia.com/community/?subtopic=characters&name='.$player["name"].'" target="blank">Tibia</a><br>';
    /* Attempt too see if character is deleted */
    if(count($profileData) < 9){
        #print_r($profileData);
        if(isset($profileData["Character ".$player["name"]." does not exist."])) {
            /* Player has been delted, lets get a link out so we can manually check */
            $db->query("UPDATE players SET deleted = 1 WHERE id = :charid");
            $db->bind(":charid", $player["id"]);
            $db->execute();
            /* Avoid double entries */
            $db->query("SELECT id FROM players_deleted WHERE charid = :charid");
            $db->bind(":charid", $player["id"]);
            $db->execute();
            if ($db->rowcount() == 0) {
                $db->query("INSERT INTO players_deleted (charid, deleteddate) VALUES(:charid, :updated)");
                $db->bind(":charid", $player["id"]);
                $db->bind(":updated", time());
                $db->execute();
            }
        }
    } else {
        $vocation = getVocationId($profileData["vocation"]);
        $characterSex = getSexId($profileData["sex"]);

        /* Store and check all deaths */
        foreach ($profileData["deaths"] as $death) {
            if (!isset($death["pvp"])) {
                $date = strtotime($death["date"]);
                /* Since it is IMPOSSIBLE to die on the same second, we check if a death like it already exists */
                $db->query("SELECT id FROM player_deaths WHERE date = :date");
                $db->bind(":date", $date);
                $db->execute();
                if ($db->rowcount() == 0) {
                    /* No death found on this second, lets save it  */
                    $db->query("INSERT INTO player_deaths (date, reason, level, charid) VALUES(:date, :reason, :level, :charid)");
                    $db->bind(":date", $date);
                    $db->bind(":reason", $death["reason"]);
                    $db->bind(":level", $death["level"]);
                    $db->bind(":charid", $player["id"]);
                    $db->execute();
                }
            }
        }
        /* Store updated date */
        $db->query("UPDATE players SET profileupdated = :updated, vocation = :voc, level = :level, sex = :sex WHERE id = :id");
            $db->bind(":voc", $vocation);
            $db->bind(":level", $profileData["level"]);
            $db->bind(":id", $player["id"]);
            $db->bind(":sex", $characterSex);;
            $db->bind(":updated", $updateDate);
            $db->bind(":id", $player["id"]);
        $db->execute();
    }
    $profileData = null; # Empty variable to reduce RAM usage
    $lasttime = time();
}

/* Lastly we delete any duplicate deletions entries we find */
$db->query("DELETE n1 FROM players_deleted n1, players_deleted n2 WHERE n1.id > n2.id AND n1.charid = n2.charid");
$db->execute();