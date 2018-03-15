<?php
class Views extends Controller {
	function __construct(){
		parent::__construct();
	}
	public function template($file, $vars = null){

		/* Global variables */
		$vars["siteName"] = configItem("siteName");
		$vars["siteHeader"] = configItem("siteHeader");
		/* Site title */
		if(isset($vars["pageTitle"])){
			$vars["siteTitle"] = $vars["pageTitle"] . ' - '. $vars["siteName"];
		} else {
			$vars["siteTitle"] = $vars["siteName"];
		}
		$vars["page"] = strtolower($this->getController());
		/* Extracting variables so we can easily use them in the views */
		if(isset($vars) && is_array($vars)){
			extract($vars);
		}
		
		/* Load header file */
		include(APPLICATION.'views/layout/header.php');
		/* Load view */
		if(file_exists(APPLICATION.'views/'.$file.'.php')){
			include(APPLICATION.'views/'.$file.'.php');
		} else {
			include(APPLICATION.'views/error/viewnotfound.php');
		}
		/* Load footer file */
		include(APPLICATION.'views/layout/footer.php');
	}

	public function view($file, $vars = null){
		/* Global variables */
		$vars["siteName"] = configItem("siteName");
		$vars["siteHeader"] = configItem("siteHeader");
		/* Site title */
		if(isset($vars["pageTitle"])){
			$vars["siteTitle"] = $vars["pageTitle"] . ' - '. $vars["siteName"];
		} else {
			$vars["siteTitle"] = $vars["siteName"];
		}
		$vars["page"] = strtolower($this->getController());
		/* Extracting variables so we can easily use them in the views */
		if(isset($vars) && is_array($vars)){
			extract($vars);
		}
		if(file_exists(APPLICATION.'views/'.$file.'.php')){
			include(APPLICATION.'views/'.$file.'.php');
		} else {
			include(APPLICATION.'views/error/viewnotfound.php');
		}
	}
}