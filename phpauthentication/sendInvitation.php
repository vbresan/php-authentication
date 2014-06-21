<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/InputValidator.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");
include_once(dirname(__FILE__) . "/class/Properties.class.php");
include_once(dirname(__FILE__) . "/class/TempInvitation.class.php");

include_once(dirname(__FILE__) . "/lib/i18n.lib.php");
include_once(dirname(__FILE__) . "/lib/password.lib.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @param string $name
 * @param string $eMail
 * @param string $message
 * 
 * @return boolean
 */
function isInputValid($name, $eMail, $message) {
	
	return strlen($name)    > 0 &&
		   strlen($message) > 0 &&
		   InputValidator::isEMailAddressValid($eMail);
}

/**
 * @param string $eMail
 * @return boolean
 */
function isAlreadyRegistered($eMail) {
	
	global $m;
	
	return $m->find("from User as u where u.eMailAddress = ?", $eMail);
}

/**
 * @param string $eMail
 * @return boolean
 */
function isAlreadyInvited($eMail) {
	
	global $m;
	
	return $m->find("from TempInvitation as t where t.eMailAddress = ?", $eMail);
}

/**
 * @return string
 */
function getRegistrationCode() {
	
	if (Properties::getProperty("General", "CheckForRegistrationCode")) {
		return generatePassword(10);
	} else {
		return "";
	}
}

/**
 * @param string $registrationCode
 * @param string $eMailAddress
 */
function saveInvitation($registrationCode, $eMailAddress) {
	
	global $m;
	
	$time = time();
	
	$invitation = $m->create("TempInvitation");
	$invitation->setRegistrationCode($registrationCode);
	$invitation->setEMailAddress($eMailAddress);
	$invitation->setCreationTime($time);

	$m->commit($invitation);	
}

/**
 * @param string $name
 * @param string $eMail
 * @param string $message
 * @param string $registrationCode
 */
function mailInvitation($name, $eMail, $message, $registrationCode) {
	
	$subject = Properties::getProperty("InvitationMail", "MailSubject");
	$subject = str_replace("{\$name}", $name, $subject);
	
	$tailFile = Properties::getProperty("InvitationMail", "MailBodyTailFile");
	$tail     = file_get_contents(dirname(__FILE__) . $tailFile);
	$tail     = str_replace("{\$registrationCode}", $registrationCode, $tail);
	
	$message .= $tail;
	
	$headersFile = Properties::getProperty("InvitationMail", "MailHeadersFile");
	$headers     = file_get_contents(dirname(__FILE__) . $headersFile);			     				
	
	mail($eMail, $subject, $message, $headers);
}

/**
 * 
 */
function decrementRemainingInvitations() {
	
	global $m;
	
	$user  = $m->get("User", CurrentUser::getUser_ID());
	$count = $user->getRemainingInvitations();
	$user->setRemainingInvitations(--$count);
	
	$m->commit($user);
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("SendInvitation");

$name  = isset($_POST["name"])         ? $_POST["name"]         : "";
$eMail = isset($_POST["eMailAddress"]) ? $_POST["eMailAddress"] : "";
$body  = isset($_POST["message"])      ? $_POST["message"]      : "";

if (! isInputValid($name, $eMail, $body)) {
	$message->display(true, msg("INVALID_INPUT_DATA"));
	die();
}

if (isAlreadyRegistered($eMail)) {
	$message->display(true, msg("USER_ALREADY_REGISTERED"));
	die();
}

if (isAlreadyInvited($eMail)) {
	$message->display(true, msg("USER_ALREADY_INVITED"));
	die();
}

$registrationCode = getRegistrationCode();
saveInvitation($registrationCode, $eMail);
mailInvitation($name, $eMail, $body, $registrationCode);
decrementRemainingInvitations();

$message->display(false, msg("USER_INVITED"));

?>