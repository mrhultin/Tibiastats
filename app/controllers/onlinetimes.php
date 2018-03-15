<?php

class Onlinetimes extends Controller {
    private $views;
    private $data;
    function __construct(){
        parent::__construct();
        $this->views = load_core("Views");
    }

    public function index(){
        $this->views->template("onlinetimes/home");
    }

    public function character($vars){
        $this->index();
    }
}