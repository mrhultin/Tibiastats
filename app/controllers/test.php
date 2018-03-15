<?php
class Test extends Controller {
    function __construct(){
        parent::__construct();
    }

    function index(){
        include(SYSTEM.'/lib/tibiaparse.php');
        $tibia = new TibiaParser;
        print_r($tibia->getHighscores("Beneva", "experience", 1));
    }
}