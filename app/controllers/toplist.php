<?php

class Toplist extends Controller {
	private $views;
	private $data;
	function __construct(){
		parent::__construct();
		$this->views = load_core("Views");
	}	
	
	public function index($vars = ''){
		$this->experience();
	}

	public function experience(){
		$this->data["toplistName"] = "Experience";
		$this->data["pageTitle"] = "Experience top 500";
		$updateDate = mktime(4, 0, 0, date("m"), date("d"), date("Y"));
		if(date("H") <= 05){
			$updateDate = $updateDate - (24*60*60);
		}
		$this->db->query("SELECT experiencehistory.id, players.worldid,  max(experiencehistory.experience) as experience, players.name, worlds.name AS worldname, max(experiencehistory.level) as level FROM experiencehistory
		JOIN players ON experiencehistory.characterid = players.id
		JOIN worlds ON players.worldid = worlds.id
		WHERE players.deleted = 0
		GROUP BY experiencehistory.characterid
		ORDER BY experiencehistory.experience DESC, experiencehistory.id ASC LIMIT 500");
		$topexp = $this->db->resultset();
		$this->data["toplisttable"] = '<table class="ui celled striped table compact">
		<thead>
			<th width="6%">Rank</th>
			<th width="22%">Name</th>
			<th>Experience</th>
			<th width="30%">Level</th>
			<th width="10%">World</th>
		</thead>';
		$i = 1;
		foreach($topexp as $row){
			$expforthislevel = expforlevel($row["level"]);
			$expfornextlevel = expforlevel($row["level"] + 1);
			$exptnl = $expfornextlevel - $expforthislevel;
			$expforthislevel = expforlevel($row["level"]);
			$expfornextlevel = expforlevel($row["level"] + 1);
			$totalexptnl = $expfornextlevel - $expforthislevel;
			$exptnl = $expfornextlevel - $row["experience"];
			/* TNL percent */
			$exptnlperc = ceil(($exptnl / $totalexptnl) * 100);
			$this->data["toplisttable"] .= '<tr><td>'.$i.'</td><td><a href="/character/view/'.$row["name"].'/">'.$row["name"].'</a></td><td>'.number_format($row["experience"]).'</td><td>'.$row["level"].' <small>('.$exptnlperc.'% left)</small></td><td><a href="/worlds/view/'.$row["worldname"].'">'.$row["worldname"].'</a></td></tr>';
			$i++;
		}
		$this->data["toplisttable"] .= '</table>';
		$this->views->template("toplist/toplist", $this->data);
	}

	public function magic(){
		$this->data["toplistName"] = "Magic";
		$this->data["pageTitle"] = "Magic top 500";
		$this->data["toplisttable"] = '<table class="ui celled striped table compact">
		<thead>
			<th width="6%">Rank</th>
			<th width="22%">Name</th>
			<th width="30%">Level</th>
			<th width="10%">World</th>
		</thead>';
		$i = 1;
		$this->db->query("SELECT players.name, players.magic, worlds.name as worldname FROM players JOIN worlds ON players.worldid = worlds.id WHERE players.deleted = 0 ORDER BY magic DESC LIMIT 500");
		$topskill = $this->db->resultset();

		foreach($topskill as $row){
			$this->data["toplisttable"] .= '<tr><td>'.$i.'</td><td><a href="/character/view/'.$row["name"].'" />'.$row["name"].'</a></td><td>'.$row["magic"].'</td><td><a href="/worlds/view/'.$row["worldname"].'">'.$row["worldname"].'</a></td></tr>';
			$i++;
		}
		$this->data["toplisttable"] .= '</table>';
		$this->views->template("toplist/toplist", $this->data);
	}

	public function axe(){
		$this->data["toplistName"] = "Axe";
		$this->data["pageTitle"] = "Axe top 500";
		$this->data["toplisttable"] = '<table class="ui celled striped table compact">
		<thead>
			<th width="6%">Rank</th>
			<th width="22%">Name</th>
			<th width="30%">Level</th>
			<th width="10%">World</th>
		</thead>';
		$i = 1;
		$this->db->query("SELECT players.name, players.axe, worlds.name as worldname FROM players JOIN worlds ON players.worldid = worlds.id WHERE players.deleted = 0 ORDER BY axe DESC LIMIT 500");
		$topskill = $this->db->resultset();

		foreach($topskill as $row){
			$this->data["toplisttable"] .= '<tr><td>'.$i.'</td><td><a href="/character/view/'.$row["name"].'" />'.$row["name"].'</a></td><td>'.$row["axe"].'</td><td><a href="/worlds/view/'.$row["worldname"].'">'.$row["worldname"].'</a></td></tr>';
			$i++;
		}
		$this->data["toplisttable"] .= '</table>';
		$this->views->template("toplist/toplist", $this->data);
	}

	public function sword(){
		$this->data["toplistName"] = "Sword";
		$this->data["pageTitle"] = "Sword top 500";
		$this->data["toplisttable"] = '<table class="ui celled striped table compact">
		<thead>
			<th width="6%">Rank</th>
			<th width="22%">Name</th>
			<th width="30%">Level</th>
			<th width="10%">World</th>
		</thead>';
		$i = 1;
		$this->db->query("SELECT players.name, players.sword, worlds.name as worldname FROM players JOIN worlds ON players.worldid = worlds.id WHERE players.deleted = 0 ORDER BY sword DESC LIMIT 500");
		$topskill = $this->db->resultset();

		foreach($topskill as $row){
			$this->data["toplisttable"] .= '<tr><td>'.$i.'</td><td><a href="/character/view/'.$row["name"].'" />'.$row["name"].'</a></td><td>'.$row["sword"].'</td><td><a href="/worlds/view/'.$row["worldname"].'">'.$row["worldname"].'</a></td></tr>';
			$i++;
		}
		$this->data["toplisttable"] .= '</table>';
		$this->views->template("toplist/toplist", $this->data);
	}

	public function club(){
		$this->data["toplistName"] = "Club";
		$this->data["pageTitle"] = "Club top 500";
		$this->data["toplisttable"] = '<table class="ui celled striped table compact">
		<thead>
			<th width="6%">Rank</th>
			<th width="22%">Name</th>
			<th width="30%">Level</th>
			<th width="10%">World</th>
		</thead>';
		$i = 1;
		$this->db->query("SELECT players.name, players.club, worlds.name as worldname FROM players JOIN worlds ON players.worldid = worlds.id WHERE players.deleted = 0 ORDER BY club DESC LIMIT 500");
		$topskill = $this->db->resultset();

		foreach($topskill as $row){
			$this->data["toplisttable"] .= '<tr><td>'.$i.'</td><td><a href="/character/view/'.$row["name"].'" />'.$row["name"].'</a></td><td>'.$row["club"].'</td><td><a href="/worlds/view/'.$row["worldname"].'">'.$row["worldname"].'</a></td></tr>';
			$i++;
		}
		$this->data["toplisttable"] .= '</table>';

		$this->views->template("toplist/toplist", $this->data);
	}

	public function distance(){
		$this->data["toplistName"] = "Distance";
		$this->data["pageTitle"] = "Distance top 500";
		$this->data["toplisttable"] = '<table class="ui celled striped table compact">
		<thead>
			<th width="6%">Rank</th>
			<th width="22%">Name</th>
			<th width="30%">Level</th>
			<th width="10%">World</th>
		</thead>';
		$i = 1;
		$this->db->query("SELECT players.name, players.distance, worlds.name as worldname FROM players JOIN worlds ON players.worldid = worlds.id WHERE players.deleted = 0 ORDER BY distance DESC LIMIT 500");
		$topskill = $this->db->resultset();

		foreach($topskill as $row){
			$this->data["toplisttable"] .= '<tr><td>'.$i.'</td><td><a href="/character/view/'.$row["name"].'" />'.$row["name"].'</a></td><td>'.$row["distance"].'</td><td><a href="/worlds/view/'.$row["worldname"].'">'.$row["worldname"].'</a></td></tr>';
			$i++;
		}
		$this->data["toplisttable"] .= '</table>';

		$this->views->template("toplist/toplist", $this->data);
	}

	public function shielding(){
		$this->data["toplistName"] = "Shielding";
		$this->data["pageTitle"] = "Shielding top 500";
		$this->data["toplisttable"] = '<table class="ui celled striped table compact">
		<thead>
			<th width="6%">Rank</th>
			<th width="22%">Name</th>
			<th width="30%">Level</th>
			<th width="10%">World</th>
		</thead>';
		$i = 1;
		$this->db->query("SELECT players.name, players.shielding, worlds.name as worldname FROM players JOIN worlds ON players.worldid = worlds.id WHERE players.deleted = 0 ORDER BY shielding DESC LIMIT 500");
		$topskill = $this->db->resultset();

		foreach($topskill as $row){
			$this->data["toplisttable"] .= '<tr><td>'.$i.'</td><td><a href="/character/view/'.$row["name"].'" />'.$row["name"].'</a></td><td>'.$row["shielding"].'</td><td><a href="/worlds/view/'.$row["worldname"].'">'.$row["worldname"].'</a></td></tr>';
			$i++;
		}
		$this->data["toplisttable"] .= '</table>';
		$this->views->template("toplist/toplist", $this->data);
	}
}
