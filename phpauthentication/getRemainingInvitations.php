<?php


session_start();

ini_set("log_errors", 1);
ini_set("display_errors", 0);

include_once(dirname(__FILE__) . "/class/CurrentUser.class.php");
include_once(dirname(__FILE__) . "/class/MessageFactory.class.php");
include_once(dirname(__FILE__) . "/class/Properties.class.php");

include_once(dirname(__FILE__) . Properties::getProperty("EZPDO", "RelativePath") . "/ezpdo_runtime.php");

$m = epManager::instance();

/*******************************************************************************
 * Start
 ******************************************************************************/

$message = MessageFactory::getMessage("RemainingInvitations");

$user_ID = CurrentUser::getUser_ID();

if ($user_ID != CurrentUser::$ID_ANONYMOUS) {
	
	$user = $m->get("User", $user_ID);
	$remainingInvitations = $user->getRemainingInvitations();
	if ($remainingInvitations > 0) {
		$message->displayRemainingInvitations($remainingInvitations);
		die();
	}
} 

$message->displayRemainingInvitations(0);

?>