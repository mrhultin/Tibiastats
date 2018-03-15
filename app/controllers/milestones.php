<?php

class Milestones extends Controller
{
    private $views;
    private $data = array();

    function __construct()
    {
        parent::__construct();
        $this->views = load_core("Views");
    }

    public function index($vars = '')
    {
        $this->views->template("milestones", $this->data);
    }
}