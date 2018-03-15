<?php
class Deleted extends Controller {
    private $views;
    private $data = array();
    function __construct(){
        parent::__construct();
        $this->views = load_core("Views");
        $this->data["pageTitle"] = "Deleted characters";
    }

    public function index($vars){
        /* Page stuff */
        $rowPerPage = 50;
        $page = 0;
        if(isset($vars)){
            $page = $vars[1]-1; # Reduce by 1 for easier calculations.
        }
        $this->data["currentPage"] = $page+1;
        $start = $page*$rowPerPage;
        $end   = $start+$rowPerPage;
        /* World data */
        $this->db->query("SELECT id, name FROM worlds ORDER BY name ASC");
        $this->data["worlds"] = $this->db->resultset();
        /* List */
        /* URL Schematics
            /controller/method/page/world/minlevel/maxlevel/vocation/
        */

        $baseUrl = "/deleted/page/%s/";
        $urlParts = array();
        /* Specific search */
        $minlevel = 0;
        $maxlevel = 9999;
        $vocationlow = 0;
        $vocationtop = 8;
        $world = "none";
        $worldsQuery = '';
        $vocationQuery = '';
        /* Get Data */

        if(isset($_POST["world"]) and strlen($_POST["world"]) > 3 and $_POST["world"] != "none"){
            $world = $_POST["world"];
            $worldsQuery = 'AND worlds.name = "'.filter_var(trim($_POST["world"]), FILTER_SANITIZE_STRING).'"';
            $this->data["searchWorld"] = trim($_POST["world"]);
            $urlParts[] = $world;
        } elseif(isset($vars[2]) and strlen($vars[2]) > 3 and $vars[2] !="none"){
            $world = $vars[2];
            $worldsQuery = 'AND worlds.name = "'.filter_var(trim($vars[2]), FILTER_SANITIZE_STRING).'"';
            $this->data["searchWorld"] = trim($vars[2]);
            $urlParts[] = $world;
        }
        $urlParts[] = $world;

        if(isset($_POST["minlevel"]) and $_POST["minlevel"] > 0){
            $minlevel = $_POST["minlevel"];
            $this->data["minlevel"] = $minlevel;
        } elseif(isset($vars[3]) and $vars[3] > 0){
            $minlevel = $vars[3];
            $this->data["minlevel"] = $minlevel;
        }

        $urlParts[] = $minlevel;

        if(isset($_POST["maxlevel"]) and $_POST["maxlevel"] > 0 and $_POST["maxlevel"] > $minlevel and $_POST["maxlevel"] != 9999){
            $maxlevel = $_POST["maxlevel"];
            $this->data["maxlevel"] = $maxlevel;
            $urlParts[] = $maxlevel;
        } elseif(isset($vars[4]) and $vars[4] > 0 and $vars[4] >= $minlevel and $vars[4] != 9999){
            $maxlevel = $vars[4];
            $this->data["maxlevel"] = $maxlevel;
            $urlParts[] = $maxlevel;
        }
        $urlParts[] = 0;


        if(isset($_POST["vocation"]) and strlen($_POST["vocation"]) > 4) {
            $lowvoc = getVocationId($_POST["vocation"]);
            $highvoc = $lowvoc + 4;
            $vocationQuery = "AND vocation = ".$lowvoc." OR vocation = ".$highvoc."";
            $this->data["selectedVoc"] = $_POST["vocation"];
            $urlParts[] = $this->data["selectedVoc"];
        } elseif(isset($vars[5]) and strlen($vars[5]) > 4){
            $lowvoc = getVocationId($vars[5]);
            $highvoc = $lowvoc + 4;
            $vocationQuery = "AND vocation = ".$lowvoc." OR vocation = ".$highvoc."";
            $this->data["selectedVoc"] = $vars[5];
            $urlParts[] = $this->data["selectedVoc"];

        }

        /*if(isset($_POST["world"]) and strlen($_POST["world"]) > 3 and $_POST["world"] != "none"){
            /* First we check if the world exists * /

            #}
        } else {
            $urlParts[] = "none";
        }
        if(isset($_POST["minlevel"]) and $_POST["minlevel"] > 0){
            $minlevel = $_POST["minlevel"] or $vars[3];
            $this->data["minlevel"] = $minlevel;
            $urlParts[] = $minlevel;
        } else {
            $urlParts[] = 0;
        }
        if(isset($_POST["maxlevel"]) and $_POST["maxlevel"] > 0){


        } else {
            $urlParts[] = 0;
        }

        if(isset($_POST["vocation"]) and strlen($_POST["vocation"]) > 4){
            $lowvoc = getVocationId($_POST["vocation"]);
            $highvoc = $lowvoc + 4;
            $vocationQuery = "AND vocation = ".$lowvoc." OR vocation = ".$highvoc."";
            $this->data["selectedVoc"] = $_POST["vocation"];
            $urlParts[] = $_POST["vocation"];
        } else {
            $urlParts[] = "all";
        }


        /* Glue the URL togheter */
        if(count($urlParts) > 0){
            foreach($urlParts as $part){
                $baseUrl .= $part.'/';
            }
        }
        $this->data["baseUrl"] = $baseUrl;
        $this->db->query("SELECT players.id, players.name,  players.level, worlds.name as worldname, deleteddate, players.vocation FROM players_deleted
        JOIN players ON players_deleted.charid = players.id
        JOIN worlds ON players.worldid = worlds.id
        WHERE level >= :min and level <= :max
        ".$vocationQuery."
        ".$worldsQuery."
        ORDER BY `players_deleted`.`deleteddate` DESC
        LIMIT :start, :end");
            $this->db->bind(":start", $start);
            $this->db->bind(":end", $rowPerPage);
            $this->db->bind(":min", $minlevel);
            $this->db->bind(":max", $maxlevel);
        $this->data["deleted"] = $this->db->resultset();
        /* Pagination */
        $this->db->query("SELECT players.id FROM players_deleted
        JOIN players ON players_deleted.charid = players.id
        JOIN worlds ON players.worldid = worlds.id
        WHERE level >= :min and level <= :max
        ".$vocationQuery."
        ".$worldsQuery."
        ORDER BY `players_deleted`.`deleteddate` DESC");
            $this->db->bind(":min", $minlevel);
            $this->db->bind(":max", $maxlevel);
        $this->db->execute();
        $totalcount = $this->db->rowcount();
        $this->data["totalpages"] = ceil($totalcount/$rowPerPage);

        $this->data["start"] = $start;
        $this->data["end"] = $end;
        $this->data["total"] = $totalcount;
        $this->views->template("deleted/overview", $this->data);
    }

    public function page($vars){

        /* Charts SELECT count(id) as count, deleteddate FROM `players_deleted` GROUP BY deleteddate ORDER BY count DESC */
        /* This is a workaround for URLs to work optimally */
        $this->index($vars);
    }
}