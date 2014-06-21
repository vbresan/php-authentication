<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/Properties.class.php");
include_once(dirname(__FILE__) . "/class/TempVerification.class.php");

include_once(dirname(__FILE__) . "/lib/i18n.lib.php");
include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

header("Content-type: text/html; charset=UTF-8");

/*******************************************************************************
 * Functions
 ******************************************************************************/

/**
 * @param TempVerification $verification
 */
function verifyUser($verification) {
	
	global $m;
	
	$user_ID = $verification->getUser_ID();
	
	$user = $m->get("User", $user_ID);
	$user->setIsVerified(true);
	$m->commit($user);
}

/*******************************************************************************
 * Start
 ******************************************************************************/

$verificationCode = isset($_GET["verificationCode"]) ? $_GET["verificationCode"] : "";

$search = $m->create("TempVerification");
$search->setVerificationCode($verificationCode);

$verifications = $m->find($search);
if ($verifications) {
	
	foreach ($verifications as $verification) {
		
		verifyUser($verification);
		$m->delete($verification);
	}
		
	$userMessage = msg("VERIFICATION_OK");
	
} else {

	$userMessage = msg("VERIFICATION_NOK");
}

?>