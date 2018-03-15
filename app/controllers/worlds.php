<?php
class Worlds extends Controller {
	private $views;
	private $data = array();
	function __construct(){
		parent::__construct();
		$this->views = load_core("Views");
	}	
	
	public function index($vars = ''){
		$this->db->query("SELECT * FROM worlds ORDER BY name ASC");
		$this->data["worlds"] = $this->db->resultset();
		$this->views->template("worlds/overview", $this->data);
	}
	public function view($vars){
		if(!isset($vars)){
			$this->index();
		} else {
			$world = $vars[1];
			$this->db->query("SELECT * FROM worlds WHERE name = :world");
			$this->db->bind(":world", $world);
			$data = $this->db->single();

			/* Type */
			if(isset($vars[2])) {
				$type = $vars[2];
				switch ($type) {
					case "magic":
						$this->skills("magic", $vars, $data);
						break;
					case "axe":
						$this->skills("axe", $vars, $data);
						break;
					case "sword":
						$this->skills("sword", $vars, $data);
						break;
					case "club":
						$this->skills("club", $vars, $data);
						break;
					case "distance":
						$this->skills("distance", $vars, $data);
						break;
					case "shielding":
						$this->skills("shielding", $vars, $data);
						break;
					default:
						/* Fail safe if we don't have a highscore requested*/
						$this->experience($vars, $data);
						break;
				}
			} else {
				$this->experience($vars, $data);
			}

		}
	}
	public function experience($vars, $data){
		if(!isset($vars)){
			$this->index();
		} else {
			$this->data["toplistName"] = 'Experience';
			$this->data["pageTitle"] = $data["name"].' experience toplist';
			$today = mktime(4, 0, 0, date("m"), date("d"), date("Y"));
		
			/* Calculate 7 days date */
			$seventhday = $today - 604800;
			/* Calculate 30 days date */
			$thirtydays = $today - 2592000;
			/* Simple values */
			$this->data["name"] = $data["name"];
			$this->data["updated"] = date("d/m/Y", $data["updated"]);
			$this->data["expupdated"] = date("d/m/Y", $data["expupdated"]);
			$this->data["location"] = $data["location"];
			$this->data["type"] = $data["type"];
			/* Player values
				TODO: Add changes and display % To next level (TNL)
			*/
			$this->db->query("SELECT players.dailychange, players.weeklychange, players.monthlychange, experiencehistory.characterid, experiencehistory.date, players.name,  experiencehistory.experience, experiencehistory.id, experiencehistory.level, experiencehistory.date FROM experiencehistory
JOIN players ON experiencehistory.characterid = players.id WHERE experiencehistory.worldid = :wid AND experiencehistory.date = :date AND players.deleted = 0
 ORDER BY experiencehistory.id ASC, experiencehistory.experience DESC LIMIT 300");
				$this->db->bind(":wid", $data["id"]);
				$this->db->bind(":date", $data["expupdated"]);
			$charlist = $this->db->resultset();

			$this->data["toplist"] = null;
			/* Build a query and fetch it all at once */
			foreach($charlist as $rank => $cdata){
				/* Get daily change */
				#$this->db->query("SELECT experiencechange, date FROM experiencehistory WHERE characterid = :cid AND date <= :date LIMIT 30");
				#	$this->db->bind(":cid", $cdata["characterid"]);
				#	$this->db->bind(":date", $thirtydays);
				#results = $this->db->resultset();
				#print_r($results);
				$weeklyexp = 0;
				$monthlyexp = 0;
				$dailyexpchange = 0;

				$results = array();
				foreach($results as $row){
					if($row["date"] == $today) {
						$dailyexpchange = formatExpChange($row["experiencechange"], $cdata["experience"]);
					}
					if($row["date"] <= $seventhday){
						$weeklyexp += $row["experiencechange"];
					}
					if($row["date"] <= $thirtydays){
						$monthlyexp += $row["experiencechange"];
					}
				}
				$dailyexpchange = formatExpChange($cdata["dailychange"], $cdata["experience"]);
				$weeklyexp = formatExpChange($cdata["weeklychange"], 0);
				$monthlyexp = formatExpChange($cdata["monthlychange"], 0);
				$rank = $rank + 1;
				$this->data["toplist"] .= '<tr>
					<td>'.$rank.'</td>
					<td><a href="/character/view/'.$cdata["name"].'">'.$cdata["name"].'</a></td>
					<td>'.$cdata["level"].'</td>
					<td>'.number_format($cdata["experience"]).'</td>
					<td>'.$dailyexpchange.'</td>
					<td>'.$weeklyexp.'</td>
					<td>'.$monthlyexp.'</td>
				</tr>';
			}
			/* Load View */
			$this->views->template("worlds/view", $this->data);
			}
		}

	public function skills($skill, $vars, $data){

		$this->data["skill"] = ucfirst($skill);

		$this->data["name"] = $data["name"];
		$this->data["pageTitle"] = $data["name"].' highscores for '.$this->data["skill"];
		$this->data["updated"] = date("d/m/Y", $data["updated"]);
		$this->data["expupdated"] = date("d/m/Y", $data["expupdated"]);
		$this->data["location"] = $data["location"];
		$this->data["type"] = $data["type"];
		$this->db->query("SELECT ".$skill.", ".$skill."rank, level, name FROM players WHERE worldid = :id AND ".$skill."rank > 0 ORDER BY ".$skill."rank ASC LIMIT 300");
			$this->db->bind(":id", $data["id"]);
		$results = $this->db->resultset();
		$this->data["toplist"] = null;
		$this->data["toplistName"] = ucfirst(strtolower($this->data["skill"]));
		foreach($results as $row){
			$this->data["toplist"] .= '<tr>
			<td>'.$row[$skill."rank"].'</td>
			<td><a href="/character/view/'.$row["name"].'">'.$row["name"].'</a></td>
			<td>'.$row[$skill].'</td>
			<td>'.$row["level"].'</td>
</tr>';
		}

		$this->views->template("worlds/viewskills", $this->data);
	}
}