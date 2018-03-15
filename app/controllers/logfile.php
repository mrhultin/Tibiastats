<?php

class Logfile extends Controller {
    private $views;
    private $data = array();
    function __construct(){
        parent::__construct();
        $this->views = load_core("Views");
        $this->data["pageTitle"] = "Update logs";
    }

    public function index(){
        $this->db->query("SELECT * FROM cronlog ORDER BY date DESC LIMIT 100");
        $this->data["logdata"] = $this->db->resultset();
        $this->views->template("logfile", $this->data);
    }
}