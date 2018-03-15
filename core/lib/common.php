<?php
/* Compare two dates, ignoring the time of the day. 
Runs the dates as if the input date always happens at 4:00 (4AM)
*/
function lastDay($old, $new = 0){
	if($new == 0){
		$new = time();
	}
	$formatedOld = mktime(4, 0, 0, date("H", $old), date("d", $old)+1, date("Y", $old));
	return $formatedOld <= $new;
}

function getCompareDate($date){
	$time = mktime(4, 0, 0, date("H", $old), date("d", $old)-1, date("Y", $old));
}

function configItem($item){
	if(!isset($config) and file_exists(APPLICATION.'config.php')){
		include(APPLICATION.'config.php');
	}
	if(isset($config[$item])){
		return $config[$item];
	}
	return false;
}

/* Tested and working stuff */
function &load_core($class, $dir = 'lib'){
	static $_loaded_core = array();
	$class = strtolower($class);
	/* Allows us to easily check if the class is a userclass or system class, system has priority */
	$fullpath = SYSTEM.$dir.'/'.$class.'.php';
	if(file_exists($fullpath) && !isset($_loaded_core[$class])){
		include($fullpath);
	}
	/*
		Check if class exists. If not, load it and return it.
	*/
	if(class_exists($class) && !isset($_loaded_core[$class])){
		$_loaded_core[$class] = new $class;
	}
	return $_loaded_core[$class];
}

function expforlevel($level){
	$level--;
	$level = (string) $level;
	return bcdiv(bcadd(bcsub(bcmul(bcmul(bcmul("50", $level), $level), $level),  bcmul(bcmul("150", $level), $level)), bcmul("400", $level)), "3", 0);
}

/* Redirects you  to a diffrent page mor easily. */
function redirect($uri = '', $method = 'location', $http_response_code = 302){

	switch($method)
	{
		case 'refresh'	: header("Refresh:0;url=".$uri);
			break;
		default			: header("Location: ".$uri, TRUE, $http_response_code);
			break;
	}
	exit;
}

function formatExpChange($val, $totalExp){
	$experienceChange = 0;
	if(($val < 0 or $val > 0) and $val != $totalExp){
		if($val > 0) {
			$experienceChange = '<span class="change-positive">+ ';
		} else {
			$experienceChange = '<span class="change-negative">- ';
		}
		$experienceChange .= number_format(abs($val)).'</span>';
	}
	return $experienceChange;
}

function formatRankChange($rank){
	$rankChange = 0;
	return $rankChange;
}

function getVocationId($name){
	$voc = configItem("vocations");
	foreach($voc as $key => $value){
		if(strtolower($value) == strtolower($name)){
			return $key;
		}
	}
	return 0;
}

function getVocationName($id){
	$voc = configItem("vocations");
	if(isset($voc[$id])){
		return $voc[$id];
	}
	return $voc[0];
}

function getSexId($sex){
	$sexes = configItem("gender");
	foreach($sexes as $key => $value){
		if($value == $sex){
			return $key;
		}
	}
	return 0;
}

function getSexName($id){
	$sexes = configItem("gender");
	if(isset($sexes[$id])){
		return $sexes[$id];
	}
	return $sexes[0];
}

function calulatePartyDiffrence($level){
	$shareRange = array(
		"low" => ceil(($level/3)*2),
		"high" => floor($level + $level/2)
	);
	return $shareRange;
}

function write_log($vars){
	die("Log file logic missing!");
}