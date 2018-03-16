<?php
/* Profitzka Tibia Website */
/* This website comes with a few demonstrative functions for parsing information from Tibia.com, you may use this as you wish but you are required to put a clear credit to who made it if you do. */
/* Definitions */
set_time_limit(1000000);
define("SYSTEM", sha1(time())); # This definition allows us to check for odd loadings and to make sure files cannot be directly accessed.
/* Load config */
include('../app/config.php');
/* Load router */
include('../core/lib/mysql.php');
$db = new Database(array("host" => $config["dbCredentials"]["host"], "user" => $config["dbCredentials"]["user"], "pass" => $config["dbCredentials"]["pass"], "database" => $config["dbCredentials"]["database"]));
/* Tibia Stuff */
$worldsupdated = 0;
include('../core/lib/tibiaparse.php');
$tibia = new TibiaParser;
$updateDate = mktime(4, 0, 0, date("m"), date("d"), date("Y"));

/* set where to store certain things */
$skillsAndStorage = array(
    "magic" => array(
        "skill" => "magic",
        "rank"  => "magicrank",
        "updated" => "magicupdated"
    ),
    "sword" => array(
        "skill" => "sword",
        "rank"  => "swordrank",
        "updated" => "swordupdated"
    ),
    "distance" => array(
        "skill" => "distance",
        "rank"  => "distancerank",
        "updated" => "distanceupdated"
    ),
    "axe" => array(
        "skill" => "axe",
        "rank"  => "axerank",
        "updated" => "axeupdated"
    ),
    "club" => array(
        "skill" => "club",
        "rank"  => "clubrank",
        "updated" => "clubupdated"
    ),
    "shielding" => array(
        "skill" => "shielding",
        "rank"  => "shieldingrank",
        "updated" => "shieldingupdated"
    ),
    "achievements" => array(
        "skill" => "achievements",
        "rank"  => "achievementsrank",
        "updated" => "achievementsupdated"
    ),
);

$db->query("SELECT id, name FROM worlds ORDER BY name ASC");
$worlds = $db->resultset();
/* We grab each worlds page 1 first, then all worlds page 2 etc etc */
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$lasttime = 0;
$loadedRows = 0;
foreach($skillsAndStorage as $skill => $storage){
    foreach($worlds as $currentWorld){
        /* Check if we need to update */
        $fourtyhours = time() - 60*60*48;
        #for($i = 1; $i <= 12; $i++) {
            #$raw = $tibia->getHighscores($currentWorld["name"], $skill, $i);
            $raw = json_decode(file_get_contents("https://api.tibiadata.com/v2/highscores/".$currentWorld["name"]."/".$skill.".json"), TRUE);
            echo "https://api.tibiadata.com/v2/highscores/".$world["name"]."/".$skill.".json";
            foreach ($raw["highscores"]["data"] as $row) {
                /* Check if player exists */
                if(!empty($row["name"])){
                $db->query("SELECT id, worldid FROM players WHERE name = :name");
                    $db->bind(":name", $row["name"]);
                $cData = $db->single();
                /* Reset each rank, to avoid double ranked players or entries */
                $db->query("UPDATE players SET ".$storage["rank"]." = 0 WHERE ".$storage["rank"]." = :rank AND worldid = :world");
                    $db->bind(":rank", $row["rank"]);
                    $db->bind(":world", $currentWorld["id"]);
                $db->execute();
                if(isset($cData["id"]) and $cData["id"] >= 0){
                    /* Player exists, check if world matches AND if world id match. */
                    $db->query("UPDATE players SET ".$storage["skill"]." = :value, ".$storage["rank"]." = :rank WHERE id = :id");
                        $db->bind(":id", $cData["id"]);
                        $db->bind(":value", $row["level"]);
                        $db->bind(":rank", $row["rank"]);
                    $db->execute();
                } else { # New player
                    $db->query("INSERT INTO players (name, worldid, " . $storage["skill"] . ", " . $storage["rank"] . ") VALUES(:name, :world, :value, :rank)");
                    $db->bind(":name", $row["name"]);
                    $db->bind(":world", $currentWorld["id"]);
                    $db->bind(":value", $row["level"]);
                    $db->bind(":rank", $row["rank"]);
                    $db->execute();
                }
                }
            }
        #}
        /* Write to Cronlog */
        $db->query("INSERT into cronlog (type, text, date) VALUES(:type, :text, :date)");
            $db->bind(":type", 2);
            $db->bind(":text", "[".ucfirst($currentWorld["name"])."] Updated ".ucfirst($skill));
            $db->bind(":date", time());
        $db->execute();
    }
}
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo '<hr>'.$loadedRows.' pages loaded in '.$total_time.' seconds.';