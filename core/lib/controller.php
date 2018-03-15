<?php

class Controller extends Router {
	function __construct(){
		/* This allows us to use Router functions under the $this variable */
		parent::__construct();
		$dbConfig = configItem("dbCredentials");
		$this->db = new Database(array("host" => $dbConfig["host"], "user" => $dbConfig["user"], "pass" => $dbConfig["pass"], "database" => $dbConfig["database"]));
		$this->cache = load_core("cache");
	}
	
	public function executeController($c){
		$c = strtolower($c);
		$path = APPLICATION.'controllers/'.$c.'.php';
		if(file_exists($path)){
			include($path);
			/* Initialize controller */
				$cc = new $c;
			
			if(method_exists($cc, $this->getMethod())){	
				/* Lets run the function and supply the vars that could be submitted. */
				call_user_func_array(array($cc, $this->getMethod()), array($this->getParameters()));
			}
		} else {
				die("Controller not found [".$this->getController()."]");
		}
	}
}