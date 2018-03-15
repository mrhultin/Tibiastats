<?php

class Session extends Controller {
    private $session = null;
    private $sessionname = "default";
    function __construct($name = null){
        if(isset($name)){
            $this->sessionname = $name;
        }
        $this->session = $this->loadsession();
    }

    private function storesession(){
        $_SESSION[$this->sessionname] = json_encode($this->session);
    }

    private function loadsession()
    {
        if (isset($_SESSION[$this->sessionname])) {
            return json_decode($_SESSION[$this->sessionname]);
        }
        return false;
    }

    public function setitem($item, $value){
        $this->session[$item] = $value;
        $this->storesession();
    }

    public function getitem($item){
        if(!isset($this->session[$item])){
            return false;
        } else {
            return $this->session[$item];
        }
    }
}