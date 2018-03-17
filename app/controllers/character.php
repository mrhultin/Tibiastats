<?php

class Character extends Controller {
	private $views;
	private $data = array();

	function __construct()
	{
		parent::__construct();
		$this->views = load_core("Views");
	}

	public function index($vars = '')
	{

		$vocationSum = 0;
		$this->data["pageTitle"] = "Character overview";
		$this->db->query("SELECT experiencehistory.id, players.name as charname, worlds.name, experiencechange FROM experiencehistory
 		LEFT JOIN players ON experiencehistory.characterid = players.id
 		LEFT JOIN worlds  ON experiencehistory.worldid = worlds.id
 		ORDER BY `experiencehistory`.`date` DESC, `experiencehistory`.`experiencechange` DESC LIMIT 5");
		$this->data["topgain"] = $this->db->resultset();
		$this->db->query("SELECT experiencehistory.id, players.name as charname, worlds.name, experiencechange FROM experiencehistory
 		LEFT JOIN players ON experiencehistory.characterid = players.id
 		LEFT JOIN worlds  ON experiencehistory.worldid = worlds.id
 		ORDER BY `experiencehistory`.`date` DESC, `experiencehistory`.`experiencechange` ASC LIMIT 5");
		$this->data["toploss"] = $this->db->resultset();
		/* Vocations */
		$this->db->query("SELECT count(id) as num FROM players WHERE vocation = 1 OR vocation = 5");
		$data = $this->db->single();
		$this->data["druidCount"] = number_format($data["num"]);
			$vocationSum += number_format($data["num"]);
		$this->db->query("SELECT count(id) as num FROM players WHERE vocation = 2 OR vocation = 6");
		$data = $this->db->single();
		$this->data["sorcCount"] = number_format($data["num"]);
			$vocationSum += number_format($data["num"]);
		$this->db->query("SELECT count(id) as num FROM players WHERE vocation = 3 OR vocation = 7");
		$data = $this->db->single();
		$this->data["pallyCount"] = number_format($data["num"]);
			$vocationSum += number_format($data["num"]);
		$this->db->query("SELECT count(id) as num FROM players WHERE vocation = 4 OR vocation = 8");
		$data = $this->db->single();
		$this->data["knightCount"] = number_format($data["num"]);
			$vocationSum += number_format($data["num"]);

		/* Vocation percentage */
			$this->data["druidPercent"] = round(calcPercent($vocationSum, $this->data["druidCount"]), 2);
			$this->data["sorcererPercent"] = round(calcPercent($vocationSum, $this->data["sorcCount"]), 2);
			$this->data["pallyPercent"] = round(calcPercent($vocationSum, $this->data["pallyCount"]), 2);
			$this->data["knightPercent"] = round(calcPercent($vocationSum, $this->data["knightCount"]), 2);
		$this->views->template("character/home", $this->data);
	}

	public function view($vars)
	{
		if (!isset($vars)) {
			return $this->index(); # Character name must be supplied.
		} else {
			$today = mktime(4, 0, 0, date("m"), date("d"), date("Y"));
			/* Calculate 7 days date */
			$seventhday = $today - 604800;
			/* Calculate 30 days date */
			$thirtydays = $today - 2592000;
			/* Get character information */
			$this->db->query("SELECT weeklychange, monthlychange, sex, players.deleted, players.name AS charname, vocation, players.id as Pid, expupdated, worlds.name AS worldname, magic, magicrank, sword, swordrank, axe, axerank, club, clubrank, distance, distancerank, shielding, shieldingrank FROM players JOIN worlds ON worldid = worlds.id WHERE players.name = :name");
			$this->db->bind(":name", $vars[1]);
			$results = $this->db->single();
			if (!$results or count($results) < 1) {
				$this->data["pageTitle"] = "Character not found";
				$this->views->template("character/characternotfound");
			} else {
				$this->data["deleted"] = $results["deleted"];
				$this->data["name"] = $results["charname"];
				$this->data["worldname"] = $results["worldname"];
				$this->data["vocation"] = getVocationName($results["vocation"]);

				$this->data["pageTitle"] = 'Profile of '.$this->data["name"];

				$gender = "He";
				if ($results["sex"] == 1) {
					$gender = "She";
				}
				$this->data["gender"] = $gender;


				$charid = $results["Pid"];
				$monthlyGain = 0;
				$weeklyGain = 0;
				$monthlyGain = 0;
				/* Get exp history */
				$this->db->query("SELECT date, level, rank, rankchange, experiencechange, experience FROM experiencehistory WHERE characterid = :pid ORDER BY id DESC LIMIT 30");
				$this->db->bind(":pid", $charid);
				$exphistory = $this->db->resultset();
				foreach ($exphistory as $row) {
					$experienceChange = 0;
					if ($row["date"] >= $thirtydays and $row["experiencechange"] != $row["experience"]) {
						if ($row["date"] >= $seventhday) {
							$weeklyGain += $row["experiencechange"];
						}
						$monthlyGain += $row["experiencechange"];
					}
					/* We want to exclude the first entry which experience will show a total sum of charactesr experience as daily gain */
					$experienceChange = formatExpChange($row["experiencechange"], $row["experience"]);
					/* Calculated until next level */
					$expforthislevel = expforlevel($row["level"]);
					$expfornextlevel = expforlevel($row["level"] + 1);
					$totalexptnl = $expfornextlevel - $expforthislevel;
					$exptnl = $expfornextlevel - $row["experience"];
					/* TNL percent */
					$exptnlperc = ceil(($exptnl / $totalexptnl) * 100);
					$rankchange = null;
					if (($row["rankchange"] < 0 or $row["rankchange"] > 0) and $row["rank"] != $row["rankchange"]) {
						if ($row["rankchange"] < 0) {
							$rankchange = ' (<span class="change-positive"><i class="arrow up icon"></i>' . abs($row["rankchange"]) . '</span>)';
						} else {
							$rankchange = ' (<span class="change-negative"><i class="arrow down icon"></i>' . $row["rankchange"] . '</span>)';
						}
					}
					$this->data["experience"][] = array(
						"date" => $row["date"],
						"rank" => $row["rank"],
						"level" => $row["level"],
						"rankchange" => $rankchange,
						"experiencechange" => $experienceChange,
						"experience" => $row["experience"],
						"tnl" => $exptnl,
						"tnlperc" => $exptnlperc,
					);
					#$weeklyGain = $experienceChange;
					#$monthlyGain   = $experienceChange;

				}
				/* Highscores */
				$this->data["highscores"] = array();
				if (count($exphistory) > 0 and $exphistory[0]["date"] <= $today) {
					$this->data["highscores"]["level"] = array(
						"skillname" => "Level",
						"skillvalue" => $exphistory[0]["level"],
						"skillrank" => $exphistory[0]["rank"],
					);
				}
				$highscoreTypes = array(
					"magic" => "Magic level",
					"axe" => "Axe Fighting",
					"sword" => "Sword Fighting",
					"club" => "Club Fighting",
					"distance" => "Distance Fighting",
					"shielding" => "Shielding Skill"
				);
				foreach ($highscoreTypes as $db => $display) {
					if ($results[$db] > 0) {
						$this->data["highscores"][$db] = array(
							"skillname" => $display,
							"skillvalue" => $results[$db],
							"skillrank" => $results[$db . "rank"]
						);
					}
				}
				#print_r($exphistory[0]);


				/* Player deaths */
				$this->db->query("SELECT date, reason, level FROM player_deaths WHERE charid = :charid ORDER BY date DESC LIMIT 10");
				$this->db->bind(":charid", $charid);
				$this->data["deaths"] = $this->db->resultset();


				/* Pass data */
				$this->data["monthlyGain"] = number_format($results["monthlychange"]);
				$this->data["weeklyGain"] = number_format($results["weeklychange"]);
				$this->data["weeklyAvg"] = number_format($results["weeklychange"] / 7);
				$this->data["monthlyAvg"] = number_format($results["monthlychange"] / 30);
				/* Select the characters best ever day */
				$this->db->query("SELECT experiencechange FROM experiencehistory WHERE characterid = :charid AND experiencechange != experience ORDER BY experiencechange DESC LIMIT 1");
				$this->db->bind(":charid", $charid);
				$dailyGain = $this->db->single();
				$this->data["bestDay"] = number_format($dailyGain["experiencechange"]);
				$this->views->template("character/view", $this->data);
			}
		}
	}

	public function search($vars)
	{
		if($_POST["charactername"]){
			$query = $_POST["charactername"];
		} elseif(isset($vars[1])){
			$query = $vars[1];
		} else {
			$query = null;
		}
		/* Check if character exist, if so we redirect, otherwise wildcard search */
		$this->data["searchterm"] = $query;
		$this->data["pageTitle"] = "Search results for: ".$query;
		if (strlen($query) <= 2) {
			$this->views->template("character/search_toshort", $this->data);
		} else {
			$this->db->query("SELECT name FROM players WHERE name = :name");
			$this->db->bind(":name", $query);
			$result = $this->db->single();
			if (isset($result) AND isset($result["name"])) {
				redirect("/character/view/" . $query . "/");
			} else {
				$this->db->query("SELECT players.name AS charname, worlds.name, max(experiencehistory.level) as level FROM players JOIN worlds on players.worldid = worlds.id JOIN experiencehistory ON players.id = experiencehistory.characterid WHERE players.name LIKE :name GROUP BY players.id ORDER BY players.name ASC");
				$this->db->bind(":name", '%' . $query . '%');
				foreach ($this->db->resultset() as $row) {
					$this->data["characters"][] = array(
						"name" => $row["charname"],
						"world" => $row["name"],
						"level" => $row["level"]
					);
				}
				$this->views->template("character/search_found", $this->data);
			}
		}
	}

	public function search_ajax($vars)
	{
		$query = $vars[1];
		$this->db->query("SELECT players.name AS charname, worlds.name, players.level as level, deleted FROM players
		JOIN worlds on players.worldid = worlds.id
		WHERE players.name LIKE :name
		GROUP BY players.id
		ORDER BY
			CASE
				WHEN players.name LIKE :like1 THEN 1
				WHEN players.name LIKE :like2 THEN 3
				ELSE 2
			END");
		$this->db->bind(":like1", $query.'%');
		$this->db->bind(":like2", '%'.$query);
		$this->db->bind(":name", '%' . $query . '%');
		$results = $this->db->resultset();
		$json_output = array(
			"action" => array(
				"url" => "/character/search/".$query."/",
				"text" => "View all ".$this->db->rowcount()." results",
			),
		);

		foreach ($results as $row) {
			$sex = "He";
			if(isset($row["sex"]) and $row["sex"] == 2){
				$sex = "She";
			}
			$level = $row["level"];
			if(isset($row["level"]) and $level == 0){
				$level = "<em>Unknown</em>";
			}
			/* Store data for output */
			$json_output["items"][] = array(
				"name" => ucfirst($row["charname"]),
				"html_url" => "/character/view/".$row["charname"]."/",
				"description" =>  'Level '.$level.' from '.$row["name"]
			);
		}
		#echo json_encode($this->data["characters"]);

		echo json_encode($json_output);
	}
}
/*
 action          : 'action',      // "view more" object name
  actionText      : 'text',        // "view more" text
  actionURL
 */