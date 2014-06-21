<?php

session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/Cookie.class.php");
include_once(dirname(__FILE__) . "/class/InputValidator.class.php");
include_once(dirname(__FILE__) . "/class/LastLogin.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");
include_once(dirname(__FILE__) . "/class/Properties.class.php");

include_once(dirname(__FILE__) . "/lib/i18n.lib.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @param User $user
 */
function setSessionData($user) {
	
	$_SESSION["user_ID"] = $user->getOid();
	Cookie::saveUser_ID($user->getOid());
}

/**
 * @param User $user
 */
function loginUser($user) {
	
	setSessionData($user);
}

/**
 * @param string $userName
 * @param string $password
 * 
 * @return boolean
 */
function isInputValid($userName, $password) {
	
	return InputValidator::isUserNameValid($userName) &&
		   InputValidator::isPasswordValid($password);
}

/**
 * @param User $user
 * @param string $userName
 * @param string $password  
 * 
 * @return boolean
 */
function isLoginDataOK($user, $userName, $password) {
	
	return $user->isUserNameOK($userName) && $user->isPasswordOK($password);
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("LogIn");

$userName = isset($_POST["userName"]) ? $_POST["userName"] : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

if (! isInputValid($userName, $password)) {
	$message->display(true, msg("INVALID_INPUT_DATA"));
	die();
}


$search = $m->create("User");
$search->setUserName($userName);

$users = $m->find($search);
if ($users && isLoginDataOK($users[0], $userName, $password)) {
	
	if ($users[0]->getIsVerified()) {
		
		loginUser($users[0]);
		
		LastLogin::refresh($users[0]->getOid());
		$message->display(false, msg("MSG_LOGIN_OK"));
		
	} else {
		
		$message->display(true, msg("ERR_LOGIN_NOT_VERIFIED"));
	}
		
} else {
	
	$message->display(true, msg("ERR_LOGIN"));
}


?>