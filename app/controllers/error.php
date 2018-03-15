<?php
class SiteError extends Controller {
	private $views;
	function __construct(){
		parent::__construct();
		$this->views = load_core("Views");
		$this->data["pageTitle"] = "Error";
	}	
	
	public function page_not_found($vars = ''){
		$this->views->template("error/404", $this->data);
	}
}