<?php
// We need to force the time to CEST
date_default_timezone_set("Europe/Berlin");
/**
 * Author: Johan Hultin
 * Last revision: 2013-06-29 21:13:00
 */

class TibiaParser
{
	private $online = true;
	private $vocations = array("None", "Druid", "Elder Druid", "Sorcerer", "Master sorcerer", "Paladin", "Royal Paladin", "Knight", "Elite knight", "Unknown");
	public function vocationId($vocation){
		$vocations = $this->vocations;
		return array_search($vocation, $vocations);
	}
    /**
     * Gets the list of the character's deaths
     *
     * @param string $name the character name
     * @return array the deaths
     */
    public function characterDeaths($name, $html = null)
    {
        if(!isset($html)){ $html = $this->getUrl("http://www.tibia.com/community/?subtopic=characters&name=".$name); }

        if (false !== stripos($html, "<b>Could not find character</b>")) {
            return $deaths["deleted"] = true;
        }

        $domd = $this->getDOMDocument($html);
        $domx = new DOMXPath($domd);
        $rows = $domx->query("//b[text() = 'Character Deaths']/ancestor::table[1]//tr[position() > 1]");
        $deaths = array();

        foreach ($rows as $row) {
            $date = $row->firstChild->nodeValue;
            $text = $row->lastChild->nodeValue;

            preg_match("/Died at Level (\\d+) by (.+)\\./", $text, $matches);
			if(count($matches) == 0){
				$deaths[] = array(
					"pvp" => true,
				);
			} else {
				$deaths[] = array(
					"date" => $date,
					"level" => $matches[1],
					"reason" => $matches[2],
				);
			}
        }

        return $deaths;
    }
    /**
     * Gets information about the given character
     *
     * @param string $name
     * @return array character data
     */
    public function characterInfo($name)
    {
		$charName = $name;
        $html = $this->getUrl("http://www.tibia.com/community/?subtopic=characters&name=".$name);
		$character = array();
		$character["deaths"] = $this->characterDeaths($charName ,$html);

        if(!isset($character["deleted"])){
			// this will be used later while we go through all the rows in the charinfo table
			$map = array(
				"Name:" => "name",
				"Former Names:" => "former_names",
				"Sex:" => "sex",
				"Vocation:" => "vocation",
				"Level:" => "level",
				"World:" => "world",
				"Former world:" => "former_world",
				"Residence:" => "residence",
				"Achievement Points:" => "achievement_points",
				"Last login:" => "last_login",
				"Comment:" => "comment",
				"Account Status:" => "account_status",
				"Married to:" => "married_to",
				"House:" => "house",
				"Guild membership:" => "guild",
				"Comment:" => "comment",
			);

			$domd = $this->getDOMDocument($html);
			$domx = new DOMXPath($domd);


			$rows = $domx->query("//div[@class='BoxContent']/table[1]/tr[position() > 1]");
			foreach ($rows as $row) {
				$name  = trim($row->firstChild->nodeValue);
				$value = trim($row->lastChild->nodeValue);

				if (isset($map[$name])) {
					$character[$map[$name]] = $value;
				} else {
					$character[$name] = $value;
				}
			}

			# Value cleanup
			if(isset($character["last_login"])) {
				$character["last_login"] = DateTime::createFromFormat("M d Y, H:i:s T", $character["last_login"]);
			} else {
				$character["last_login"] = DateTime::createFromFormat("M d Y, H:i:s T", 0);
			}

			if (isset($character["guild"])) {
				$values = explode(" of the ", $character["guild"]);
				$character["guild"] = array(
					"name"  =>  $values[1],
					"rank"  =>  $values[0],
				);
			}

			if (isset($character["house"])) {
				$values = explode(" is paid until ", $character["house"]);
				$character["house"] = $values[0];
			}
			/* Grab deaths if there are any */

		}
        return $character;
    }

    /**
     * Return the list of characters online at the given world
     *
     * @param type $world
     * @return array characters with name, level and vocation
     */
    public function whoIsOnline($world)
    {
        $html = $this->getUrl("http://www.tibia.com/community/?subtopic=worlds&world=" . $world);
        $domd = $this->getDOMDocument($html);

        $domx = new DOMXPath($domd);
        $characters = $domx->query("//table[@class='Table2']//tr[position() > 1]");
        $ret = array();

        foreach ($characters as $character) {
            $name     = $domx->query("td[1]/a[@href]", $character)->item(0)->nodeValue;
            $level    = $domx->query("td[2]", $character)->item(0)->nodeValue;
            $vocation = $domx->query("td[3]", $character)->item(0)->nodeValue;

            $ret[] = array(
                "name"      =>  $name,
                "level"     =>  $level,
                "vocation"  =>  $vocation,
            );
        }

        return $ret;
    }
    /**
     * Retrieves a list of all worlds
     *
     * @param null
     * @return array of all worlds.
     */	
	public function getWorlds(){
		$html = $this->getUrl("http://www.tibia.com/community/?subtopic=worlds");
		
		$domd = $this->getDOMDocument($html);
		if($this->online){
			$domx = new DOMXPath($domd);
			$worlds = $domx->query("//table[@class='TableContent']//tr[position() > 1]");
			$ret = array();
			
			foreach($worlds as $world){
				$name = $domx->query("td[1]/a[@href]", $world)->item(0)->nodeValue;
				$type = $domx->query("td[4]", $world)->item(0)->nodeValue;
				$area = $domx->query("td[3]", $world)->item(0)->nodeValue;
				$online = $domx->query("td[2]", $world)->item(0)->nodeValue;
				$ret[] = array(
					"name" 		=> $name,
					"location" 	=> $area,
					"worldtype" => $type,
					"online" => $online,
				);
			}
			
			return $ret;
		}
	}
	/**
     * Retrieves a list of all worlds
     *
     * @param $world, $type, $page
     * @return array highscores data.
     */	
	public function getHighscores($world, $type, $page){
		
			$type = strtolower($type);

			$html = $this->getUrl("http://www.tibia.com/community/?subtopic=highscores&world=".$world."&list=".$type."&profession=0&currentpage=".$page);
			$domd = $this->getDOMDocument($html);
			$domx = new DOMXPath($domd);

			$worlds = $domx->query("//table[@class='TableContent']//tr[position() > 1]");
			$ret = array();
			foreach($worlds as $world){
				@$rank = $domx->query("td[1]", $world)->item(0)->nodeValue;
				@$name = $domx->query("td[2]/a[@href]", $world)->item(0)->nodeValue;
				@$vocation = $domx->query("td[3]", $world)->item(0)->nodeValue;
				if($type == 'experience'){
					@$level = $domx->query("td[4]", $world)->item(0)->nodeValue;
					@$skill = str_replace(",", "", $domx->query("td[5]", $world)->item(0)->nodeValue);
				} else {
					@$skill = $domx->query("td[4]", $world)->item(0)->nodeValue;
				}
				$ret[] = array(
					"name" 		=> @$name,
					"rank" 	=> @$rank,
					"value" => @$skill,
					"level" => @$level,
				);
			}
			unset($ret[0]); // To prevent an extra row!
			return $ret;

	}
    /**
     * Creates a DOMDocument object from a given html string
     *
     * @param string $html
     * @return DOMDocument
     */
    private function getDOMDocument($html)
    {
		$domd = new DOMDocument("1.0", "utf-8");
        $replace = array(
            "&#160;"    =>  " ", // non-breaking space in the page's code
            chr(160)    =>  " ", // non-breaking space in character comments
        );
        $html = str_replace(array_keys($replace), array_values($replace), $html);

        $html = mb_convert_encoding($html, "utf-8", "iso-8859-1");

        libxml_use_internal_errors(true);
        $domd->loadHTML($html);
        libxml_use_internal_errors(false);
		
        return $domd;
    }

	public function isOnline(){
		return $this->online;
	}
    /**
     * Fetches a page from tibia.com and returns its body
     *
     * @param string $url
     * @return string
     * @throws \RuntimeException if a http error occurs
     */
    private function getUrl($url)
    {	
         // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
		if($httpcode>=200 && $httpcode<300){  
			$this->online = true;
		} else {  
			$this->online = false;
		}  
		return $output; 
    }
}
?>