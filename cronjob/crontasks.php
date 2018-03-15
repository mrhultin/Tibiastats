<?php
/* This classes job is to handle and schedule recurring crontasks. It will also provide basic features such as database connectivity etc to successive crontasks */
class crontasks {
    private $config;
    public $db;
    public $tibia;

    function __construct(){
        /* Load configuration */
        $this->init_config();
        /* Initialize database */
        $this->init_database();
        /* Initialize Tibia class */
        $this->init_tibia();
		
		/* Unfinished DO NOT use */
		$this->execute_cron();
    }

    private function init_config(){
        if(!isset($config)){
            include(APPLICATION."config.php");
        }
        $this->config = $config;
    }

    private function init_database(){
        $dbFileLoaded = false;
        foreach(get_included_files() as $file){
            if($file == $this->dbFile){
                $dbFileLoaded = true;
            }
        }
        if(!$dbFileLoaded){
            include(SYSTEM."lib/mysql.php");
        }
        $this->db = new Database(array("host" => $this->config["dbCredentials"]["host"], "user" => $this->config["dbCredentials"]["user"], "pass" => $this->config["dbCredentials"]["pass"], "database" => $this->config["dbCredentials"]["database"]));
    }
}