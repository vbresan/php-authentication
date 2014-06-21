<?php


/**
 * @param int $length
 * @return string
 */
function generatePassword($length = 8) {
	
    $password = "";
    $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz023456789"; 
	    
    $i = 0;

    while ($i < $length) {

    	$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
	    if (! strstr($password, $char)) {
	    	$password .= $char;
	        $i++;
	    }
	}
	
	return $password;
}

/**
 * 
 * @param $password
 * @param $timestamp
 * 
 * @return string
 */
function saltPassword($password, $timestamp) {

	return md5($password . md5(date("r", $timestamp)));
}

?>
