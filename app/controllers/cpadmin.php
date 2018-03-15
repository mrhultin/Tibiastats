<?php

class Cpadmin extends Controller {
    private $views;
    private $data = array();
    function __construct(){
        parent::__construct();
        $this->views = load_core("Views");
        $this->data["pageTitle"] = "About us";
        $this->session = load_core("session");
        print_r($_SESSION["default"]);
        $this->session->setitem("test", "test");
    }

    public function index(){
        $this->views->template("about", $this->data);
    }
}