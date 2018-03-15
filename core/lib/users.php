<?php

class Users extends Database {
    function __construct(){
        parent::__construct();
    }

    function getUserId(){
        return 1;
    }



    function hashPassword($raw){
        $userId = $this->getUserId();
        if(!isset($raw) or !isset($userId)){
            return false;
        }
        # Adding a salt based on the 6 to 14th character of userid hash
        $salt = substr(hash('sha256', $userId), 6, 8);
        $saltedPassword = $raw.$salt;

        # Hashed password
        return hash('sha256', $saltedPassword);
    }
}