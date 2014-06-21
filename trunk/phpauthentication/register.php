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
 * @return int
 */
function getNumberOfInvitations() {
	
	$numberOfInvitations = Properties::getProperty("General", "NumberOfInvitations");
	if (isset($numberOfInvitations)) {
		return $numberOfInvitations;
	} else {
		return 0;
	}
}

/**
 * @param string $userName
 * @param string $password
 * @param string $eMailAddress
 * 
 * @return User
 */
function createUser($userName, $password, $eMailAddress) {
	
	global $m;
	
	$timestamp = time();

	$user = $m->create("User");
	$user->setUserName($userName);
	$user->setPassword(saltPassword($password, $timestamp));
	$user->setEMailAddress($eMailAddress);
	$user->setGender("0");
	$user->setMemberSince($timestamp);
	$user->setRemainingInvitations(getNumberOfInvitations());
	
	return $user;
}

/**
 * @param User $user
 * @return TempVerification 
 */
function createTempVerification($user) {

	global $m;
	
	$time = time();
	
	$verification = $m->create("TempVerification");
	$verification->setUser_ID($user->getOid());
	$verification->setVerificationCode($time . "-" . rand(1000000000, 9999999999));
	$verification->setCreationTime($time);
	
	return $verification;
}

/**
 * @param User $user
 * @param TempVerification $verification
 */
function sendMail($user, $verification) {
	
	$link    = $verification->getLink();
	$to      = $user->getEMailAddress();
	$subject = Properties::getProperty("VerificationMail", "MailSubject");
	
	$messageFile = Properties::getProperty("VerificationMail", "MailBodyFile");
	$message     = file_get_contents(dirname(__FILE__) . $messageFile);
	$message     = str_replace("{\$link}", $link, $message);

	$headersFile = Properties::getProperty("VerificationMail", "MailHeadersFile");
	$headers     = file_get_contents(dirname(__FILE__) . $headersFile);

	mail($to, $subject, $message, $headers);
}

/**
 * @param string $userName
 * @param string $password
 * @param string $eMailAddress
 * 
 * @return boolean
 */
function isInputValid($userName, $password, $eMailAddress) {
	
	return InputValidator::isUserNameValid($userName) &&
		   InputValidator::isPasswordValid($password) &&
		   InputValidator::isEMailAddressValid($eMailAddress);
}

/**
 * @param string $registrationCode
 * @return TempInvitation
 */
function getTempInvitationUsingCode($registrationCode) {
	
	global $m;
	global $message;
	
	$tempInvitations = $m->find("from TempInvitation as t where t.registrationCode = ?", $registrationCode);
	if ($tempInvitations) {
		return $tempInvitations[0];
	} else {
		$message->display(true, msg("INVALID_REGISTRATION_CODE"));
		die();
	}
}

/**
 * @param string $eMailAddress
 * @return TempInvitation
 */
function getTempInvitationUsingEMailAddress($eMailAddress) {
	
	global $m;
	
	$tempInvitations = $m->find("from TempInvitation as t where t.eMailAddress = ?", $eMailAddress);
	if ($tempInvitations) {
		return $tempInvitations[0];
	} else {
		return null;
	}	
}

/**
 * @param TempInvitation $tempInvitation
 */
function deleteTempInvitation($tempInvitation) {
	
	global $m;
	
	if ($tempInvitation) {
		
		try {
			$m->delete($tempInvitation);
		} catch (Exception $e) {
			// do nothing
		}
	}	
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("UserRegistration");

$userName         = isset($_POST["userName"])         ? $_POST["userName"]         : "";
$password         = isset($_POST["password"])         ? $_POST["password"]         : "";
$eMailAddress     = isset($_POST["eMailAddress"])     ? $_POST["eMailAddress"]     : "";
$registrationCode = isset($_POST["registrationCode"]) ? $_POST["registrationCode"] : "";


if (! isInputValid($userName, $password, $eMailAddress)) {
	$message->display(true, msg("INVALID_INPUT_DATA"));
	die();
}

if (Properties::getProperty("General", "CheckForRegistrationCode")) {
	$tempInvitation = getTempInvitationUsingCode($registrationCode);	
} else {
	$tempInvitation = getTempInvitationUsingEMailAddress($eMailAddress);
}


$user = createUser($userName, $password, $eMailAddress); 

try {
	
	$m->commit($user);
	deleteTempInvitation($tempInvitation);
	
	if (Properties::getProperty("General", "SendVerificationEMail")) {

		$verification = createTempVerification($user);
		$m->commit($verification);

		sendMail($user, $verification);
		$message->display(false, msg("MSG_VERIFICATION_MAIL_SENT"));
		
	} else {
		
		$user->setIsVerified(true);
		$m->commit($user);
		$message->display(false, msg("REGISTER_OK"));
	}
	
} catch (Exception $e) {
	
	$message->display(true, msg("ERR_MSG_DUPLICATE_USER_DATA"));
}

?>