<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/class/InputValidator.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");
include_once(dirname(__FILE__) . "/class/Properties.class.php");

include_once(dirname(__FILE__) . "/lib/i18n.lib.php");
include_once(dirname(__FILE__) . "/lib/password.lib.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @param string $currentPassword
 * @param string $newPassword
 * @param string $eMailAddress
 * @param int $gender
 * @param int $birthYear
 * 
 * @return boolean
 */
function isInputValid($currentPassword, $newPassword, $eMailAddress, $gender, $birthYear) {
	
	return InputValidator::isPasswordValid($currentPassword) &&
		   InputValidator::isPasswordValid($newPassword) &&
		   InputValidator::isEMailAddressValid($eMailAddress) &&
		   InputValidator::isGenderValid($gender) &&
		   InputValidator::isBirthYearValid($birthYear);
}

/**
 * @param $user User
 * @param $password string
 * @param $eMailAddress string
 * @param $gender string
 * @param $birthYear string
 */
function setPrivateData($user, $password, $eMailAddress, $gender, $birthYear) {
	
	global $m;
	global $message;
	
	$user->setPassword(saltPassword($password, $user->getMemberSince()));
	$user->setEMailAddress($eMailAddress);
	$user->setGender($gender);
	$user->setBirthYear($birthYear);
		
	try {
		$m->commit($user);
		$message->display(false, msg("CHANGE_DATA_OK"));
		die();
	} catch (Exception $e) {
		$message->display(true, msg("CHANGE_DATA_NOK"));
		die();
	}	
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("SetPrivateData");

$currentPassword = isset($_POST["currentPassword"]) ? $_POST["currentPassword"] : "";
$newPassword     = isset($_POST["newPassword"])     ? $_POST["newPassword"]     : "";
$eMailAddress    = isset($_POST["eMailAddress"])    ? $_POST["eMailAddress"]    : "";
$gender          = isset($_POST["gender"])          ? $_POST["gender"]          : "";
$birthYear       = isset($_POST["birthYear"])       ? $_POST["birthYear"]       : "";

if ($birthYear == "") {
	$birthYear = 0;
}

if (! isInputValid($currentPassword, $newPassword, $eMailAddress, $gender, $birthYear)) {
	$message->display(true, msg("INVALID_INPUT_DATA"));
	die();
}


$user_ID = CurrentUser::getUser_ID();
if ($user_ID != CurrentUser::$ID_ANONYMOUS) {
	
	$user = $m->get("User", $user_ID);
	if ($user->isPasswordOK($currentPassword)) {
		setPrivateData($user, $newPassword, $eMailAddress, $gender, $birthYear);
	} else {
		$message->display(true, msg("ERR_PASSWORD"));
	}
} else {
	$message->display(true, msg("ERR_SHOULD_LOG_IN"));
}

?>