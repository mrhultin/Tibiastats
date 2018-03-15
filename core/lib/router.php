<?php 
if(!defined("SYSTEM")){
	die("Direct access to this file is prohibited");
}
class Router {
	private $controller;
	private $method;
	private $parameters;
	private $rawData;
	private $defaultController;
	
	function __construct(){
		$this->defaultController = 'home';
		$this->setRawData();
		
		/* Set values */
		$this->controller = $this->setController();
		$this->method     = $this->setMethod();
		$this->parameters = $this->setParameters();
	}

	private function setRawData(){
		if(isset($_GET["query"])){
			$query = rtrim($_GET["query"],"/");
			$this->rawData = explode('/', $query);
		}
		return true;
	}

	/* Get functions */
	public function getController(){
		return ucfirst(strtolower($this->controller));
	}
	public function getMethod(){
		return $this->method;
	}	
	public function getParameters(){
		return $this->parameters;
	}
	
	/* Set values */
	private function setController(){
		if(!is_array($this->rawData)){
			return $this->defaultController; # Default fallback controller, this is the default controller.
		} else {
			return $this->rawData[0];
		}
	}
	/* Sets the method. */
	private function setMethod(){
		if(!is_array($this->rawData) or !isset($this->rawData[1])){
			return 'index';
		} else {
			return $this->rawData[1];
		}
	}
	
	/* Returns all Parameters
		Removes Method and Controller from the rawdata array, they can be retrieved inside any controller with $this->getMethod(); $this->getController();
	*/
	private function setParameters(){
		if(is_array($this->rawData) and count($this->rawData) > 2){
			$params = array();
			foreach($this->rawData as $paramid => $param){
				if($paramid > 1){ # Exclude page and method.
					$params[$paramid-1] = $param; 
				}
			}
			/* Store value */
			return $params;
		} else {
			return null;
		}
	}
	
}