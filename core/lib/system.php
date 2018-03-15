<?php
class coresystems {
	private $db;
	private $tibia;
	
	function __construct($db, $tibia){
		$this->db = $db;
		$this->tibia = $tibia;
	}
	
	public function updateOnline(){
		$allon = $this->tibia->getWorlds();
		$lowest = array(
			"count" => null,
			"name" => null,
		);
		$highest = array(
			"count" => null,
			"name" => null,
		);
		$worlddata = $this->tibia->getWorlds();
		$worldsonline = 0;
		$worldsoffline = 0;
		$totalonline = 0;
		$select = '<option>Select a world</option>';
		$worldlist = null;
		foreach($worlddata as $w){
			$select .= '<option value="'.$w["name"].'">'.$w["name"].'</option>';
			if($w["online"] != "off"){
				if($w["online"] < $lowest["count"] or $lowest["count"] == null){
					$lowest["count"] = $w["online"];
					$lowest["name"]  = $w["name"];
				}
				if($w["online"] > $highest["count"] or $highest["count"] == null){
					$highest["count"] = $w["online"];
					$highest["name"] = $w["name"];
				}
				$totalonline += $w["online"];
				$worldsonline++;
			} else {
				$worldsoffline++;
			}
			$worldlist .= '<tr><td><a href="https://secure.tibia.com/community/?subtopic=worlds&world='.$w["name"].'">'.$w["name"].'</a></td><td>'.$w["online"].'</td><td>'.$w["worldtype"].'</td></tr>';
		}
		
		/* Save update */
		$jsonHighest = json_encode($highest);
		$jsonLowest  = json_encode($lowest);
		$average     = round($totalonline/$worldsonline,2);
		$this->db->query("INSERT INTO onlinerecords (`id`, `highest`, `lowest`, `average`, `worldcount`, `worldselect`,`timestamp`, `worldlist`) VALUES (NULL, :high, :low, :avg, :count, :select, :time, :worldlist)");
			$this->db->bind(":high", $jsonHighest);
			$this->db->bind(":low", $jsonLowest);
			$this->db->bind(":avg", $average);
			$this->db->bind(":count", $worldsonline);
			$this->db->bind(":select", $select);
			$this->db->bind(":time", time());
			$this->db->bind(":worldlist", $worldlist);
		$this->db->execute();
	}
}