<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/Properties.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");

include_once(dirname(__FILE__) . "/lib/i18n.lib.php");
include_once(dirname(__FILE__) . "/lib/password.lib.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @param User $user
 * @param string $password
 */
function sendLoginData($user, $password) {
	
	$userName = $user->getUserName();
	$to       = $user->getEMailAddress();
	$subject  = Properties::getProperty("LoginDataMail", "MailSubject");
	
	$messageFile = Properties::getProperty("LoginDataMail", "MailBodyFile");
	$message     = file_get_contents(dirname(__FILE__) . $messageFile);
	$message     = str_replace("{\$userName}", $userName, $message);
	$message     = str_replace("{\$password}", $password, $message);

	$headersFile = Properties::getProperty("LoginDataMail", "MailHeadersFile");
	$headers     = file_get_contents(dirname(__FILE__) . $headersFile);
				   	
	mail($to, $subject, $message, $headers);	
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("RequestLoginData");

$eMailAddress = isset($_POST["eMailAddress"]) ? $_POST["eMailAddress"] : "";
$eMailAddress = trim($eMailAddress);

if (strlen($eMailAddress) == 0) {
	$message->display(true, msg("INVALID_INPUT_DATA"));
	die();
}


$search = $m->create("User");
$search->setEMailAddress($eMailAddress);

$users = $m->find($search);
if ($users) {
	
	$user = $users[0];
	try {

		$password = generatePassword();
		
		$timestamp      = $user->getMemberSince(); 
		$saltedPassword = saltPassword(md5($password), $timestamp);
		
		$user->setPassword($saltedPassword);
		$user->epSetCommittable(true);
		$m->commit($user);

		sendLoginData($user, $password);
		
		$message->display(false, msg("MSG_USER_DATA_MAIL_SENT"));
		
	} catch (Exception $e) {
		$message->display(true, msg("INTERNAL_ERROR"));
	}
	
} else {
	
	$message->display(true, msg("ERR_MSG_USER_DATA"));
}

?>