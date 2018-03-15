<?php

class Calculators extends Controller {
	private $views;
	private $data = array();

	function __construct()
	{
		parent::__construct();
		$this->views = load_core("Views");
	}
	
	public function index($vars){
		$this->views->template("calculators/index", $this->data);
	}
}