<?php

class About extends Controller {
    private $views;
    private $data = array();
    function __construct(){
        parent::__construct();
        $this->views = load_core("Views");
        $this->data["pageTitle"] = "About us";
    }

    public function index(){
        $this->views->template("about", $this->data);
    }
}