<?php

class Home extends Controller {
	private $views;
	private $data = array();
	function __construct(){
		parent::__construct();
		$this->views = load_core("views");
		$this->data["pageTitle"] = "Tibia Statistics";
	}	
	
	public function index($vars = ''){
		/* Select 4 news */
		$this->db->query("SELECT * FROM news ORDER BY id DESC LIMIT 4");
		$this->data["news"] = $this->db->resultset();
		$this->db->query("SELECT id FROM players");
			$this->db->execute();
		$this->data["playerCount"] = $this->db->rowcount();
		$this->db->query("SELECT id FROM worlds");
			$this->db->execute();
		$this->data["worldCount"] = $this->db->rowcount();
		$this->db->query("SELECT id FROM player_deaths");
			$this->db->execute();
		$this->data["deathCount"] = $this->db->rowcount();
		$this->db->query("SELECT id FROM players_deleted");
			$this->db->execute();
		$this->data["deletionCount"] = $this->db->rowcount();

		$this->views->template("home", $this->data);
	}
}