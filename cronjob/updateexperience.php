<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/* Tibia Website */
/* Updates experience rankings for charactesr from Tibia.com  */
/* Definitions */
set_time_limit(100000);
define("SYSTEM", sha1(time())); # This definition allows us to check for odd loadings and to make sure files cannot be directly accessed.
/* Load config */
include('../app/config.php');
include('../core/lib/mysql.php');
$db = new Database(array("host" => $config["dbCredentials"]["host"], "user" => $config["dbCredentials"]["user"], "pass" => $config["dbCredentials"]["pass"], "database" => $config["dbCredentials"]["database"]));
/* Tibia Stuff */
include('../core/lib/tibiaparse.php');
$tibia = new TibiaParser;
$updateDate = mktime(4, 0, 0, date("m"), date("d"), date("Y")); // Todays date @4am
$sevenDays = $updateDate - 604800;
$thirtyDays = $updateDate - 2592000;
/* Fetch all worlds */
$db->query("SELECT * FROM worlds WHERE expupdated < :expupdated ORDER BY name ASC");
	$db->bind(":expupdated", $updateDate);
$worlds = $db->resultset();
$characters = json_decode(file_get_contents("https://api.tibiadata.com/v2/highscores/Antica.json"), TRUE);
print_r($characters["highscores"]["data"]);
foreach($worlds as $currentworld){
	$character = array();
	$world = array(
		"name" => $currentworld["name"], 
		"id"   => $currentworld["id"],
	);
	$db->query("SELECT expupdated FROM worlds WHERE id = :id");
		$db->bind(":id", $world["id"]);
	$expupdate = $db->single();
	$characters = json_decode(file_get_contents("https://api.tibiadata.com/v2/highscores/".$world["name"].".json"), TRUE);
		/* Store to DB */
		foreach($characters["highscores"]["data"] as $rank => $data) {
			if (strlen($data["name"]) > 0){
				$number = 1;

			/* Check if character exits */
			$name = $data["name"];
			/* Deleted = 0, as we need to create a new player if a player with a certain name exists already */
			$db->query("SELECT id FROM players WHERE name = :name AND deleted = 0");
				$db->bind(":name", $name);
			$charid = $db->single();
			if (!$charid && !empty($name)) {
				/* Save character to DB
					-- Todo: Check for OLD names to merge history.
				*/
				$db->query("INSERT INTO players (name, worldid) VALUES(:name, :wid)");
				#echo $name . '<br>';
				$db->bind(":name", $name);
				$db->bind(":wid", $world["id"]);
				$db->execute();
				$charid["id"] = $db->lastinsertid();
			}
			/* Calculate experience change and rank change and store it */
			$db->query("SELECT experience, rank, date FROM experiencehistory WHERE characterid = :charid ORDER BY date DESC LIMIT 1");
			$db->bind(":charid", $charid["id"]);
			$changes = $db->single();

			$expchange = 0;
			$rankchange = 0;
			if (isset($changes)) {
				if ($changes["rank"] !== 0) {
					$rankchange = $rank - $changes["rank"];
				}
				if ($changes["experience"] > 0) {
					/* This prevents characters full EXP from being listed as a gain */
					$expchange = $data["points"] - $changes["experience"];
				}
			}
			/* Update characters level in players table */
			$db->query("UPDATE players SET level = :level WHERE id = :charid");
			$db->bind(":level", $data["level"]);
			$db->bind(":charid", $charid["id"]);
			$db->execute();

			/* Check if character is already updated today, if not we update it */
			#$db->query("SELECT date FROM experiencehistory WHERE characterid = :cid ORDER BY DATE DESC");
			#	$db->bind(":cid", $charid["id"]);
			$lastUpdate = $changes;
			if (!$lastUpdate || $lastUpdate["date"] != $updateDate) {
				$db->query("INSERT INTO experiencehistory (characterid, worldid, date, experience, level, rank, rankchange, experiencechange) VALUES(:charid, :worldid, :date, :experience, :level, :rank, :rankchange, :experiencechange)");
					$db->bind(":charid", $charid["id"]);
					$db->bind(":worldid", $world["id"]);
					$db->bind(":date", $updateDate);
					$db->bind(":experience", $data["points"]);
					$db->bind(":level", $data["level"]);
					$db->bind(":rank", $rank);
					$db->bind(":rankchange", $rankchange);
					$db->bind(":experiencechange", $expchange);
				$db->execute();

				/* Calculate daily, weekly and monthly experience */
				$db->query("SELECT experiencechange, date FROM experiencehistory WHERE date >= :month and date != :today and characterid = :charid LIMIT 29");
					$db->bind(":month", $thirtyDays);
					$db->bind(":today", $updateDate);
					$db->bind(":charid", $charid["id"]);
				$history = $db->resultset();
				$weeklyExp = $expchange; # Start on experience change so we get todays values too!
				$monthlyExp = $expchange;
				if ($db->lastinsertid() > 0) {
					$storedrows++;
				}
				foreach ($history as $row) {
					/* Weekly */
					if ($row["date"] >= $sevenDays) {
						$weeklyExp += $row["experiencechange"];
					}
					if ($row["date"] >= $thirtyDays) {
						$monthlyExp += $row["experiencechange"];
					}
				}
				/* At this point we should have the values. Lets store them */
				$db->query("UPDATE players SET dailychange = :daily, weeklychange = :weekly, monthlychange = :monthly WHERE id = :charid");
					$db->bind(":daily", $expchange);
					$db->bind(":weekly", $weeklyExp);
					$db->bind(":monthly", $monthlyExp);
					$db->bind(":charid", $charid["id"]);
				$db->execute();

			} else {
				/* Char has been updated, storedrows need an added value though */
				$storedrows++;
			}
		}
		}
		/* Store information date info, croninformation, and reset the variables associated. */
		$db->query("UPDATE worlds SET expupdated = :date WHERE id = :wid");
			$db->bind(":date", $updateDate);
			$db->bind(":wid", $world["id"]);
		$db->execute();
		/* Write to Log */
		$db->query("INSERT INTO cronlog (type, text, date) VALUES(:type, :text, :date)");
			$db->bind(":type", 1); /* Type 1 refers to world experience */
			$db->bind(":text", "<strong>[".$world["name"]."]</strong> Updated experience.");
			$db->bind(":date", time());
		$db->execute();
		/* Flush variables to consume less RAM and CPU */
		unset($raw);
		unset($characters);
		$raw = array();
		$characters = array();
}