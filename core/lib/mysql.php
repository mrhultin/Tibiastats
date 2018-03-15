<?php
/* Make sure this file is not accessed directly! */
if(!defined('SYSTEM')){
	die("No direct access allowed!");
}
ini_set('memory_limit', '256M');
/*
Author: Johan Hultin
Git: https://github.com/mrhultin/H-CMS
Copyright 2014 all rights reserved.
*/
class Database {
	private $stmt;
    private $dbh;
    private $error;
 
    public function __construct($details = ''){
		if(!isset($details) || !is_array($details)){
			die("Details was not supplied to the db interface. Must be submited as a array value!");
		}
		foreach($details as $key => $value)
		{
			if(!defined($key))
			{
				define($key, $value);
			}
		}
        // Set DSN
        $dsn = 'mysql:host=' . host . ';dbname=' . database;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => false,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try{
            $this->dbh = new PDO($dsn, user, pass, $options);
        }
        // Catch any errors
        catch(PDOException $e){
            write_log($e->getMessage());
			die("Database connection error!");
        }
    }
	public function query($query){
		$this->stmt = $this->dbh->prepare($query);
	}
	public function bind($param, $value, $type = null){
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}
		$this->stmt->bindValue($param, $value, $type);
	}
	public function execute(){
		return $this->stmt->execute();
	}
	public function resultset(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_ASSOC);
	}
	public function rowcount(){
		return $this->stmt->rowCount();
	}
	public function lastinsertid(){
		return $this->dbh->lastInsertId();
	}
	public function begintransaction(){
		return $this->dbh->beginTransaction();
	}
	public function endtransaction(){
		return $this->dbh->commit();
	}
	public function canceltransaction(){
		return $this->dbh->rollBack();
	}
	public function debugdumpparams(){
		return $this->stmt->debugDumpParams();
	}
}